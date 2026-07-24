<x-guest-layout :heading="'Create your account'" :subheading="'Start managing properties in minutes'">
    <x-slot name="footer">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">{{ __('Sign in') }}</a>
    </x-slot>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="f_name" :value="__('First name')" />
                <x-text-input id="f_name" type="text" name="f_name" :value="old('f_name')" required autofocus autocomplete="given-name" placeholder="Dana" />
                <x-input-error :messages="$errors->get('f_name')" />
            </div>

            <div>
                <x-input-label for="l_name" :value="__('Last name')" />
                <x-text-input id="l_name" type="text" name="l_name" :value="old('l_name')" required autocomplete="family-name" placeholder="Alvarez" />
                <x-input-error :messages="$errors->get('l_name')" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Work email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@agency.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="agency_name" :value="__('Agency')" />
            <x-text-input id="agency_name" type="text" name="agency_name" :value="old('agency_name')" autocomplete="organization" placeholder="Skyline Realty" />
            <x-input-error :messages="$errors->get('agency_name')" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Create a password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button>
            {{ __('Create account') }}
        </x-primary-button>
    </form>
</x-guest-layout>
