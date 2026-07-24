@php
    $agent = $agent ?? null;
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="f_name" value="First name" />
        <x-text-input id="f_name" name="f_name" type="text" required :value="old('f_name', $agent?->f_name)" />
        <x-input-error :messages="$errors->get('f_name')" />
    </div>

    <div>
        <x-input-label for="l_name" value="Last name" />
        <x-text-input id="l_name" name="l_name" type="text" required :value="old('l_name', $agent?->l_name)" />
        <x-input-error :messages="$errors->get('l_name')" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" required :value="old('email', $agent?->email)" />
        <x-input-error :messages="$errors->get('email')" />
    </div>

    <div>
        <x-input-label for="phone_num" value="Phone" />
        <x-text-input id="phone_num" name="phone_num" type="text" :value="old('phone_num', $agent?->phone_num)" />
        <x-input-error :messages="$errors->get('phone_num')" />
    </div>

    <div>
        <x-input-label for="agency_name" value="Agency" />
        <x-text-input id="agency_name" name="agency_name" type="text" :value="old('agency_name', $agent?->agency_name)" />
        <x-input-error :messages="$errors->get('agency_name')" />
    </div>

    <div>
        <x-input-label for="license" value="License #" />
        <x-text-input id="license" name="license" type="text" :value="old('license', $agent?->license)" />
        <x-input-error :messages="$errors->get('license')" />
    </div>

    @unless ($agent)
        <div class="sm:col-span-2">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>
    @endunless
</div>

<div class="mt-4">
    <x-input-label for="bio" value="Bio" />
    <textarea id="bio" name="bio" rows="3" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('bio', $agent?->bio) }}</textarea>
    <x-input-error :messages="$errors->get('bio')" />
</div>
