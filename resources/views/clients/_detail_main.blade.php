@php
    $leadColors = [
        'New' => 'blue', 'Contacted' => 'amber', 'Qualified' => 'purple',
        'Nurturing' => 'amber', 'Client' => 'green', 'Closed' => 'gray', 'Lost' => 'red',
    ];
@endphp

<div class="space-y-6 lg:col-span-2">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $client->f_name }} {{ $client->l_name }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $client->type }} &middot; {{ $client->location ?? 'No location on file' }}</p>
            </div>
            <x-agent.pill :color="$leadColors[$client->lead_status] ?? 'gray'">
                {{ $client->lead_status }}
            </x-agent.pill>
        </div>

        <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-gray-100 pt-5 text-sm sm:grid-cols-3">
            <div>
                <dt class="text-gray-400">Email</dt>
                <dd class="mt-0.5 font-medium text-gray-900">{{ $client->email ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-400">Phone</dt>
                <dd class="mt-0.5 font-medium text-gray-900">{{ $client->phone ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-400">Lead Source</dt>
                <dd class="mt-0.5 font-medium text-gray-900">{{ $client->lead_source ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-400">Client Since</dt>
                <dd class="mt-0.5 font-medium text-gray-900">{{ $client->client_since?->format('M j, Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-400">Last Activity</dt>
                <dd class="mt-0.5 font-medium text-gray-900">{{ $client->last_activity?->format('M j, Y g:i A') ?? '—' }}</dd>
            </div>
        </dl>

        @if ($client->notes)
            <div class="mt-5 border-t border-gray-100 pt-5">
                <dt class="text-sm text-gray-400">Notes</dt>
                <dd class="mt-1 text-sm text-gray-700">{{ $client->notes }}</dd>
            </div>
        @endif
    </div>

    @if ($client->preference)
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-base font-semibold text-gray-900">Preferences</h2>
            <dl class="mt-4 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                <div>
                    <dt class="text-gray-400">Budget</dt>
                    <dd class="mt-0.5 font-medium text-gray-900">
                        ${{ number_format($client->preference->budget_min ?? 0) }} – ${{ number_format($client->preference->budget_max ?? 0) }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400">Property Type</dt>
                    <dd class="mt-0.5 font-medium text-gray-900">{{ $client->preference->pref_property_type ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Bed / Bath</dt>
                    <dd class="mt-0.5 font-medium text-gray-900">{{ $client->preference->pref_bedrooms ?? '—' }} bd / {{ $client->preference->pref_bathrooms ?? '—' }} ba</dd>
                </div>
            </dl>
        </div>
    @endif

    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h2 class="text-base font-semibold text-gray-900">Appointment History</h2>
        <div class="mt-4 space-y-3">
            @forelse ($client->appointments as $appointment)
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $appointment->title }}</p>
                        <p class="text-xs text-gray-400">{{ $appointment->start_time->format('M j, Y g:i A') }} &middot; {{ $appointment->appt_type }}</p>
                    </div>
                    <x-agent.pill color="gray">{{ $appointment->status }}</x-agent.pill>
                </div>
            @empty
                <p class="text-sm text-gray-500">No appointments yet.</p>
            @endforelse
        </div>
    </div>
</div>
