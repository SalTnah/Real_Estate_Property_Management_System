<x-agent-layout :title="'Profile'">
    <div class="max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-4">
            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-lg font-semibold text-white">
                {{ strtoupper(substr($agent->f_name, 0, 1).substr($agent->l_name, 0, 1)) }}
            </span>
            <div>
                <h1 class="text-lg font-bold text-gray-900">{{ $agent->f_name }} {{ $agent->l_name }}</h1>
                <p class="text-sm text-gray-500">{{ $agent->agency_name ?? 'No agency on file' }}</p>
            </div>
        </div>

        <dl class="mt-6 space-y-3 border-t border-gray-100 pt-5 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-400">Email</dt>
                <dd class="font-medium text-gray-900">{{ $agent->email }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">Phone</dt>
                <dd class="font-medium text-gray-900">{{ $agent->phone_num ?? '—' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-400">License #</dt>
                <dd class="font-medium text-gray-900">{{ $agent->license ?? '—' }}</dd>
            </div>
        </dl>

        @if ($agent->bio)
            <p class="mt-5 border-t border-gray-100 pt-5 text-sm text-gray-600">{{ $agent->bio }}</p>
        @endif

        <a href="{{ route('agent.profile.edit') }}" class="mt-5 inline-block rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Edit Profile
        </a>
    </div>
</x-agent-layout>
