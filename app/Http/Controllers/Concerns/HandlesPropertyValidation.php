<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Property;
use Illuminate\Http\Request;

/**
 * Shared, role-agnostic Property CRUD logic (validation + photo storage).
 *
 * Deliberately does NOT contain any query-scoping logic (agent-owned vs
 * global) — that stays separate in Agent\PropertyController and
 * Admin\PropertyController respectively, per the refactor's Rule 1/2 split.
 */
trait HandlesPropertyValidation
{
    public const TYPES = ['House', 'Condo', 'Townhouse', 'Apartment', 'Land'];

    public const STATUSES = ['Available', 'Pending', 'Sold', 'Off-market'];

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
