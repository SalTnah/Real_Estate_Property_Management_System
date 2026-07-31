@php
    $property = $property ?? null;
@endphp

@if (isset($agents))
    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Listing Agent</p>
    <div class="mt-3">
        <x-input-label for="agent_id" value="Agent" />
        <select id="agent_id" name="agent_id" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Select an agent</option>
            @foreach ($agents as $a)
                <option value="{{ $a->id }}" @selected(old('agent_id', $property?->agent_id) == $a->id)>
                    {{ $a->f_name }} {{ $a->l_name }}{{ $a->agency_name ? ' — '.$a->agency_name : '' }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('agent_id')" />
    </div>
@endif

<p class="mt-6 text-xs font-semibold uppercase tracking-wide text-gray-400">Basics</p>
<div class="mt-3">
    <x-input-label for="title" value="Property title" />
    <x-text-input id="title" name="title" type="text" required :value="old('title', $property?->title)" />
    <x-input-error :messages="$errors->get('title')" />
</div>

<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="property_type" value="Property type" />
        <select id="property_type" name="property_type" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @foreach ($types as $type)
                <option value="{{ $type }}" @selected(old('property_type', $property?->property_type) === $type)>{{ $type }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('property_type')" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $property?->status ?? 'Available') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" />
    </div>
</div>

<p class="mt-6 text-xs font-semibold uppercase tracking-wide text-gray-400">Location</p>
<div class="mt-3">
    <x-input-label for="street_address" value="Street address" />
    <x-text-input id="street_address" name="street_address" type="text" required :value="old('street_address', $property?->street_address)" />
    <x-input-error :messages="$errors->get('street_address')" />
</div>

<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div>
        <x-input-label for="city" value="City" />
        <x-text-input id="city" name="city" type="text" required :value="old('city', $property?->city)" />
        <x-input-error :messages="$errors->get('city')" />
    </div>
    <div>
        <x-input-label for="state" value="State" />
        <x-text-input id="state" name="state" type="text" required :value="old('state', $property?->state)" />
        <x-input-error :messages="$errors->get('state')" />
    </div>
    <div>
        <x-input-label for="zip" value="ZIP" />
        <x-text-input id="zip" name="zip" type="text" required :value="old('zip', $property?->zip)" />
        <x-input-error :messages="$errors->get('zip')" />
    </div>
</div>

<p class="mt-6 text-xs font-semibold uppercase tracking-wide text-gray-400">Specs &amp; Price</p>
<div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div>
        <x-input-label for="price" value="Price (USD)" />
        <x-text-input id="price" name="price" type="number" step="0.01" required :value="old('price', $property?->price)" />
        <x-input-error :messages="$errors->get('price')" />
    </div>
    <div>
        <x-input-label for="size_sqft" value="Size (sqft)" />
        <x-text-input id="size_sqft" name="size_sqft" type="number" :value="old('size_sqft', $property?->size_sqft)" />
        <x-input-error :messages="$errors->get('size_sqft')" />
    </div>
    <div>
        <x-input-label for="lot_acre" value="Lot (acre)" />
        <x-text-input id="lot_acre" name="lot_acre" type="number" step="0.01" :value="old('lot_acre', $property?->lot_acre)" />
        <x-input-error :messages="$errors->get('lot_acre')" />
    </div>
</div>

<div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div>
        <x-input-label for="bedrooms" value="Bedrooms" />
        <x-text-input id="bedrooms" name="bedrooms" type="number" min="0" max="50" :value="old('bedrooms', $property?->bedrooms)" />
        <x-input-error :messages="$errors->get('bedrooms')" />
    </div>
    <div>
        <x-input-label for="bathrooms" value="Bathrooms" />
        <x-text-input id="bathrooms" name="bathrooms" type="number" step="0.5" min="0" max="50" :value="old('bathrooms', $property?->bathrooms)" />
        <x-input-error :messages="$errors->get('bathrooms')" />
    </div>
    <div>
        <x-input-label for="year_built" value="Year built" />
        <x-text-input id="year_built" name="year_built" type="number" min="1901" max="{{ now()->year + 1 }}" placeholder="e.g. 2024" :value="old('year_built', $property?->year_built)" />
        <x-input-error :messages="$errors->get('year_built')" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="description" value="Description" />
    <textarea id="description" name="description" rows="4" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $property?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" />
</div>