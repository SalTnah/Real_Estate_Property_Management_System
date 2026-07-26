<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public const TYPES = ['House', 'Condo', 'Townhouse', 'Apartment', 'Land'];

    public const STATUSES = ['Available', 'Pending', 'Sold', 'Off-market'];

    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $this->isAdmin($user);

        if ($isAdmin) {
            $base = Property::query();
        } else {
            $agent = $user->agent;
            abort_unless($agent, 403, 'Your account is not linked to an agent profile.');
            $base = $agent->properties();
        }

        $counts = [
            'all' => (clone $base)->count(),
            'available' => (clone $base)->where('status', 'Available')->count(),
            'pending' => (clone $base)->where('status', 'Pending')->count(),
            'sold' => (clone $base)->where('status', 'Sold')->count(),
        ];

        $query = (clone $base)->with(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')]);

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
            'routePrefix' => $this->routePrefix($request),
        ]);
    }

    public function create(Request $request)
    {
        return view('properties.create', [
            'types' => self::TYPES,
            'statuses' => self::STATUSES,
            'routePrefix' => $this->routePrefix($request),
        ]);
    }

    public function filter(Request $request)
    {
        return view('properties.filter', [
            'types' => self::TYPES,
            'statuses' => ['Available', 'Pending', 'Sold'],
            'routePrefix' => $this->routePrefix($request),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        $agent = auth()->user()->agent;
        abort_unless($agent, 403, 'Only agents can create properties.');

        $property = $agent->properties()->create($validated);

        // geocode address -> lat/lng here if using a geocoding service

        $this->storeUploadedPhotos($request, $property);

        return redirect()
            ->route("{$this->routePrefix($request)}.properties.show", $property)
            ->with('success', 'Property added.');
    }

    public function show(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'), 'agent']);

        return view('properties.show', [
            'property' => $property,
            'routePrefix' => $this->routePrefix($request),
        ]);
    }

    public function edit(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')]);

        return view('properties.edit', [
            'property' => $property,
            'types' => self::TYPES,
            'statuses' => self::STATUSES,
            'routePrefix' => $this->routePrefix($request),
        ]);
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $validated = $this->validated($request);

        $property->update($validated);

        $this->storeUploadedPhotos($request, $property);

        return redirect()
            ->route("{$this->routePrefix($request)}.properties.show", $property)
            ->with('success', 'Property updated.');
    }

    public function destroy(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        foreach ($property->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_url);
        }

        $property->delete();

        return redirect()
            ->route("{$this->routePrefix($request)}.properties.index")
            ->with('success', 'Property removed.');
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

    public function photosIndex(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $property->load(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')]);

        return view('properties.photos', [
            'property' => $property,
            'routePrefix' => $this->routePrefix($request),
        ]);
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

    /**
     * Derive the route-name prefix ("agent" or "admin") from the
     * currently matched route, so shared views/redirects can build
     * the correct route name regardless of which group handled them.
     */
    private function routePrefix(Request $request): string
    {
        $name = $request->route()?->getName() ?? '';

        return Str::before($name, '.properties');
    }

    /**
     * Normalize $user->role to a plain string, whether it's a backed enum
     * (e.g. UserRole::Admin with value 'admin') or a plain string column.
     */
    private function roleValue($user): ?string
    {
        $role = $user->role;

        return $role instanceof \BackedEnum ? $role->value : $role;
    }

    private function isAdmin($user): bool
    {
        return $this->roleValue($user) === 'admin';
    }

    private function authorizeOwner(Property $property): void
    {
        $user = auth()->user();

        if ($this->isAdmin($user)) {
            return; // admins can view/manage any property
        }

        abort_unless($user->agent && $property->agent_id === $user->agent->id, 403);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:'.implode(',', self::TYPES),
            'status' => 'nullable|in:'.implode(',', self::STATUSES),
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'size_sqft' => 'nullable|integer|min:0',
            'lot_acre' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|digits:4',
        ]);
    }

    private function storeUploadedPhotos(Request $request, Property $property): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $nextOrder = (int) $property->photos()->max('sort_order');
        $hasPrimary = $property->photos()->where('is_primary', true)->exists();

        foreach ($request->file('photos') as $index => $photo) {
            $path = $photo->store('properties', 'public');

            $property->photos()->create([
                'photo_url' => $path,
                'is_primary' => ! $hasPrimary && $index === 0,
                'sort_order' => $nextOrder + $index + 1,
            ]);
        }
    }
}