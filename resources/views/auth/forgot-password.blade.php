<x-guest-layout :heading="'Reset your password'" :subheading="'We\'ll email you a link to get back in'">
    <x-slot name="footer">
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700">{{ __('Back to sign in') }}</a>
    </x-slot>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Enter your email address and we will send you a link to reset your password.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="you@agency.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>
            {{ __('Email password reset link') }}
        </x-primary-button>
    </form>
</x-guest-layout>
