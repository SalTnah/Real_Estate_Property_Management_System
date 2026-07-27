@php
    $client = $client ?? null;
@endphp

@if (isset($agents))
    <div class="mb-4">
        <x-input-label for="agent_id" value="Assigned Agent" />
        <select id="agent_id" name="agent_id" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Select an agent</option>
            @foreach ($agents as $a)
                <option value="{{ $a->id }}" @selected(old('agent_id', $client?->agent_id) == $a->id)>
                    {{ $a->f_name }} {{ $a->l_name }}{{ $a->agency_name ? ' — '.$a->agency_name : '' }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('agent_id')" />
    </div>
@endif

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="f_name" value="First name" />
        <x-text-input id="f_name" name="f_name" type="text" required :value="old('f_name', $client?->f_name)" />
        <x-input-error :messages="$errors->get('f_name')" />
    </div>

    <div>
        <x-input-label for="l_name" value="Last name" />
        <x-text-input id="l_name" name="l_name" type="text" required :value="old('l_name', $client?->l_name)" />
        <x-input-error :messages="$errors->get('l_name')" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" :value="old('email', $client?->email)" />
        <x-input-error :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" :value="old('phone', $client?->phone)" />
        <x-input-error :messages="$errors->get('phone')" />
    </div>

    <div>
        <x-input-label for="type" value="Type" />
        <select id="type" name="type" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @foreach (['Buyer', 'Seller', 'Both', 'Renter'] as $option)
                <option value="{{ $option }}" @selected(old('type', $client?->type) === $option)>{{ $option }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type')" />
    </div>

    <div>
        <x-input-label for="lead_status" value="Lead status" />
        <select id="lead_status" name="lead_status" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @foreach (['New', 'Contacted', 'Qualified', 'Nurturing', 'Client', 'Closed', 'Lost'] as $option)
                <option value="{{ $option }}" @selected(old('lead_status', $client?->lead_status) === $option)>{{ $option }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('lead_status')" />
    </div>

    <div>
        <x-input-label for="lead_source" value="Lead source" />
        <x-text-input id="lead_source" name="lead_source" type="text" :value="old('lead_source', $client?->lead_source)" placeholder="Referral, Website, Zillow..." />
        <x-input-error :messages="$errors->get('lead_source')" />
    </div>

    <div>
        <x-input-label for="location" value="Location" />
        <x-text-input id="location" name="location" type="text" :value="old('location', $client?->location)" />
        <x-input-error :messages="$errors->get('location')" />
    </div>

    <div>
        <x-input-label for="client_since" value="Client since" />
        <x-text-input id="client_since" name="client_since" type="date" :value="old('client_since', $client?->client_since?->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('client_since')" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="notes" value="Notes" />
    <textarea id="notes" name="notes" rows="4" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $client?->notes) }}</textarea>
    <x-input-error :messages="$errors->get('notes')" />
</div>

<div class="mt-8">
    <h3 class="text-base font-semibold text-gray-900 mb-4">Client Preferences</h3>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <x-input-label for="budget_min" value="Budget min" />
            <x-text-input id="budget_min" name="budget_min" type="number" step="0.01" min="0"
                :value="old('budget_min', $client?->preference?->budget_min)" />
            <x-input-error :messages="$errors->get('budget_min')" />
        </div>

        <div>
            <x-input-label for="budget_max" value="Budget max" />
            <x-text-input id="budget_max" name="budget_max" type="number" step="0.01" min="0"
                :value="old('budget_max', $client?->preference?->budget_max)" />
            <x-input-error :messages="$errors->get('budget_max')" />
        </div>

        <div>
            <x-input-label for="pref_property_type" value="Preferred property type" />
            <select id="pref_property_type" name="pref_property_type" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Select a type</option>
                @foreach (['Single Family', 'Condo', 'Townhouse', 'Multi-Family', 'Land', 'Commercial'] as $option)
                    <option value="{{ $option }}" @selected(old('pref_property_type', $client?->preference?->pref_property_type) === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('pref_property_type')" />
        </div>

        <div>
            <x-input-label for="pref_areas" value="Preferred areas" />
            <x-text-input id="pref_areas" name="pref_areas" type="text"
                :value="old('pref_areas', $client?->preference?->pref_areas ? implode(', ', $client->preference->pref_areas) : null)"
                placeholder="Downtown, Westside, Lakefront..." />
            <p class="mt-1 text-xs text-gray-500">Separate multiple areas with commas.</p>
            <x-input-error :messages="$errors->get('pref_areas')" />
        </div>

        <div>
            <x-input-label for="pref_bedrooms" value="Preferred bedrooms" />
            <x-text-input id="pref_bedrooms" name="pref_bedrooms" type="number" min="0"
                :value="old('pref_bedrooms', $client?->preference?->pref_bedrooms)" />
            <x-input-error :messages="$errors->get('pref_bedrooms')" />
        </div>

        <div>
            <x-input-label for="pref_bathrooms" value="Preferred bathrooms" />
            <x-text-input id="pref_bathrooms" name="pref_bathrooms" type="number" min="0" step="0.5"
                :value="old('pref_bathrooms', $client?->preference?->pref_bathrooms)" />
            <x-input-error :messages="$errors->get('pref_bathrooms')" />
        </div>

        <div class="sm:col-span-2">
            <x-input-label for="must_haves" value="Must-haves" />
            <x-text-input id="must_haves" name="must_haves" type="text"
                :value="old('must_haves', $client?->preference?->must_haves ? implode(', ', $client->preference->must_haves) : null)"
                placeholder="Garage, Pool, Home office..." />
            <p class="mt-1 text-xs text-gray-500">Separate multiple items with commas.</p>
            <x-input-error :messages="$errors->get('must_haves')" />
        </div>
    </div>

    <div class="mt-4">
        <x-input-label for="additional_notes" value="Additional preference notes" />
        <textarea id="additional_notes" name="additional_notes" rows="4" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('additional_notes', $client?->preference?->additional_notes) }}</textarea>
        <x-input-error :messages="$errors->get('additional_notes')" />
    </div>
</div>