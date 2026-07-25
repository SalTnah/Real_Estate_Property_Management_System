<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesPropertyValidation;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    use HandlesPropertyValidation;

    public function index(Request $request)
    {
        $base = Property::query();

        $counts = [
            'all' => (clone $base)->count(),
            'available' => (clone $base)->where('status', 'Available')->count(),
            'pending' => (clone $base)->where('status', 'Pending')->count(),
            'sold' => (clone $base)->where('status', 'Sold')->count(),
        ];

        $query = Property::query()
            ->with([
                'agent',
                'photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
            ]);

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

        return view('admin.properties.index', compact('properties', 'counts', 'view'));
    }

    public function create()
    {
        return view('admin.properties.create', [
            'types' => self::TYPES,
            'statuses' => self::STATUSES,
            'agents' => Agent::orderBy('f_name')->get(),
        ]);
    }

    public function filter(Request $request)
    {
        return view('properties.filter', [
            'types' => self::TYPES,
            'statuses' => ['Available', 'Pending', 'Sold'],
            'routePrefix' => 'admin',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        // Admin creates on behalf of an agent — require agent_id explicitly.
        $validated['agent_id'] = $request->validate(['agent_id' => 'required|exists:agents,id'])['agent_id'];

        $property = Property::create($validated);

        $this->storeUploadedPhotos($request, $property);

        return redirect()->route('admin.properties.show', $property)->with('success', 'Property added.');
    }

    public function show(Property $property)
    {
        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'), 'agent']);

        return view('admin.properties.show', [
            'property' => $property,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(Property $property)
    {
        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')]);

        return view('admin.properties.edit', [
            'property' => $property,
            'types' => self::TYPES,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Property $property)
    {
        $validated = $this->validated($request);

        $property->update($validated);

        $this->storeUploadedPhotos($request, $property);

        return redirect()->route('admin.properties.show', $property)->with('success', 'Property updated.');
    }

    public function destroy(Property $property)
    {
        foreach ($property->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_url);
        }

        $property->delete();

        return redirect()->route('admin.properties.index')->with('success', 'Property removed.');
    }

    public function updateStatus(Request $request, Property $property)
    {
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
        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')]);

        return view('properties.photos', ['property' => $property, 'routePrefix' => 'admin']);
    }

    public function storePhotos(Request $request, Property $property)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|max:5120',
        ]);

        $this->storeUploadedPhotos($request, $property);

        return back()->with('success', 'Photos uploaded.');
    }

    public function setPrimaryPhoto(Property $property, $photoId)
    {
        $property->photos()->update(['is_primary' => false]);
        $property->photos()->findOrFail($photoId)->update(['is_primary' => true]);

        return back()->with('success', 'Cover photo updated.');
    }

    public function reorderPhotos(Request $request, Property $property)
    {
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
}
