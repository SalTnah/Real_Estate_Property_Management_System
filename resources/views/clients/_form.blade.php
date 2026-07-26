@php
    $client = $client ?? null;
@endphp

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
