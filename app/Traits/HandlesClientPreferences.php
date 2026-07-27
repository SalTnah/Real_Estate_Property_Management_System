<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HandlesClientPreferences
{
    protected function preferenceRules(): array
    {
        return [
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0|gte:budget_min',
            'pref_property_type' => 'nullable|string|max:191',
            'pref_bedrooms' => 'nullable|integer|min:0',
            'pref_bathrooms' => 'nullable|numeric|min:0',
            'pref_areas' => 'nullable|string',
            'must_haves' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ];
    }

    /**
     * Validate preference fields and convert comma-separated inputs
     * into arrays for the ClientPreference model's array casts.
     * Returns null if no preference fields were submitted at all.
     */
    protected function validatedPreferenceData(Request $request): ?array
    {
        $hasAnyPreferenceInput = collect(array_keys($this->preferenceRules()))
            ->contains(fn ($field) => $request->filled($field));

        if (! $hasAnyPreferenceInput) {
            return null;
        }

        $validated = $request->validate($this->preferenceRules());

        foreach (['pref_areas', 'must_haves'] as $field) {
            $validated[$field] = $validated[$field] ?? null;

            if (is_string($validated[$field])) {
                $validated[$field] = array_values(array_filter(array_map('trim', explode(',', $validated[$field]))));
            }
        }

        return $validated;
    }
}