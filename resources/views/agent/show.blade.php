<x-agent-layout :title="'Settings'">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Your profile information.</p>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:col-span-2">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $agent->f_name }} {{ $agent->l_name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $agent->agency_name ?? 'No agency on file' }}</p>
                </div>
                <a href="{{ route('agent.edit') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Edit Profile
                </a>
            </div>

            <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-gray-100 pt-5 text-sm sm:grid-cols-3">
                <div>
                    <dt class="text-gray-400">Email</dt>
                    <dd class="mt-0.5 font-medium text-gray-900">{{ $agent->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Phone</dt>
                    <dd class="mt-0.5 font-medium text-gray-900">{{ $agent->phone_num ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">License</dt>
                    <dd class="mt-0.5 font-medium text-gray-900">{{ $agent->license ?? '—' }}</dd>
                </div>
            </dl>

            @if ($agent->bio)
                <div class="mt-5 border-t border-gray-100 pt-5">
                    <dt class="text-sm text-gray-400">Bio</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $agent->bio }}</dd>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <a href="{{ route('availability.index') }}" class="block rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 hover:bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-900">Availability</h3>
                <p class="mt-1 text-xs text-gray-500">Manage the days and hours you're open for viewings.</p>
            </a>
            <a href="{{ route('notifications.index') }}" class="block rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100 hover:bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                <p class="mt-1 text-xs text-gray-500">View recent updates and alerts.</p>
            </a>
        </div>
    </div>
</x-agent-layout>
