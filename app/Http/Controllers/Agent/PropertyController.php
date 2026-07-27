<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Concerns\HandlesPropertyValidation;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    use HandlesPropertyValidation;

    /**
     * Prefix used to build route names for shared views
     * (e.g. properties.index, properties.filter, properties.create).
     */
    private string $routePrefix = 'agent';

    public function index(Request $request)
    {
        $agent = auth()->user()->agent;

        $base = $agent->properties();

        $counts = [
            'all' => (clone $base)->count(),
            'available' => (clone $base)->where('status', 'Available')->count(),
            'pending' => (clone $base)->where('status', 'Pending')->count(),
            'sold' => (clone $base)->where('status', 'Sold')->count(),
        ];

        $query = $agent->properties()->with(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')]);

        $quick = $request->string('quick')->toString();
        if (in_array($quick, self::STATUSES, true)) {
            $query->where('status', $quick);
        } elseif (in_array($quick, self::TYPES, true)) {
            $query->where('property_type', $quick);
        }

        if ($request->filled('status')) {
            $statuses = array_filter((array) $request->input('status'));
            if (! empty($statuses)) {
                $query->whereIn('status', $statuses);
            }
        }

        if ($request->filled('type') && $request->type !== 'Any') {
            $query->where('property_type', $request->type);
        }

        if ($request->filled('q')) {
            $keyword = $request->string('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('street_address', 'like', "%{$keyword}%")
                    ->orWhere('city', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        if ($request->filled('beds') && $request->beds !== 'Any') {
            $query->where('bedrooms', '>=', (int) rtrim($request->beds, '+'));
        }

        match ($request->string('sort')->toString()) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'beds' => $query->orderByDesc('bedrooms'),
            default => $query->latest(),
        };

        $view = $request->string('view')->toString() === 'list' ? 'list' : 'grid';

        $properties = $query->paginate(9)->withQueryString();

        return view('properties.index', [
            'properties' => $properties,
            'counts' => $counts,
            'view' => $view,
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function create()
    {
        return view('properties.create', [
            'types' => self::TYPES,
            'statuses' => self::STATUSES,
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function filter(Request $request)
    {
        return view('properties.filter', [
            'types' => self::TYPES,
            'statuses' => ['Available', 'Pending', 'Sold'],
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $property = auth()->user()->agent->properties()->create($validated);

        // geocode address -> lat/lng here if using a geocoding service

        $this->storeUploadedPhotos($request, $property);

        return redirect()->route('agent.properties.show', $property)->with('success', 'Property added.');
    }

    public function show(Property $property)
    {
        $this->authorizeOwner($property);

        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'), 'agent']);

        return view('agent.properties.show', [
            'property' => $property,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(Property $property)
    {
        $this->authorizeOwner($property);

        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')]);

        return view('agent.properties.edit', [
            'property' => $property,
            'types' => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $validated = $this->validated($request);

        $property->update($validated);

        $this->storeUploadedPhotos($request, $property);

        return redirect()->route('agent.properties.show', $property)->with('success', 'Property updated.');
    }

    public function destroy(Property $property)
    {
        $this->authorizeOwner($property);

        foreach ($property->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_url);
        }

        $property->delete();

        return redirect()->route('agent.properties.index')->with('success', 'Property removed.');
    }

    public function toggleFavorite(Property $property)
    {
        $this->authorizeOwner($property);

        $property->update(['is_favorited' => ! $property->is_favorited]);

        return back()->with('success', 'Favorite updated.');
    }

    public function updateStatus(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', self::STATUSES),
        ]);

        $property->update($validated);

        return back()->with('success', 'Status updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | Photo management
    |--------------------------------------------------------------------------
    */

    public function photosIndex(Property $property)
    {
        $this->authorizeOwner($property);

        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')]);

        return view('properties.photos', ['property' => $property, 'routePrefix' => $this->routePrefix]);
    }

    public function storePhotos(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|max:5120',
        ]);

        $this->storeUploadedPhotos($request, $property);

        return back()->with('success', 'Photos uploaded.');
    }

    public function setPrimaryPhoto(Property $property, $photoId)
    {
        $this->authorizeOwner($property);

        $property->photos()->update(['is_primary' => false]);
        $property->photos()->findOrFail($photoId)->update(['is_primary' => true]);

        return back()->with('success', 'Cover photo updated.');
    }

    public function reorderPhotos(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:property_photos,id',
        ]);

        foreach ($validated['order'] as $index => $photoId) {
            $property->photos()->where('id', $photoId)->update([
                'sort_order' => $index,
                'is_primary' => $index === 0,
            ]);
        }

        return back()->with('success', 'Photo order updated.');
    }

    public function destroyPhoto(Property $property, $photoId)
    {
        $this->authorizeOwner($property);

        $photo = $property->photos()->findOrFail($photoId);
        Storage::disk('public')->delete($photo->photo_url);
        $wasPrimary = $photo->is_primary;
        $photo->delete();

        if ($wasPrimary) {
            $next = $property->photos()->orderBy('sort_order')->first();
            $next?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Photo removed.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function authorizeOwner(Property $property): void
    {
        abort_unless($property->agent_id === auth()->user()->agent->id, 403);
    }
}