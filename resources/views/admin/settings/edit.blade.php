<x-agent-layout :title="'Settings'">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Update your account information.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-6 max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" type="text" required :value="old('name', $user->name)" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" required :value="old('email', $user->email)" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="New password" />
            <x-text-input id="password" name="password" type="password" autocomplete="new-password" placeholder="Leave blank to keep current password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirm new password" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="mt-6">
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                Save Changes
            </button>
        </div>
    </form>
</x-agent-layout>
