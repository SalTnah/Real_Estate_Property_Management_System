@php
    $leadColors = [
        'New' => 'blue', 'Contacted' => 'amber', 'Qualified' => 'purple',
        'Nurturing' => 'amber', 'Client' => 'green', 'Closed' => 'gray', 'Lost' => 'red',
    ];
@endphp

<x-agent-layout :title="'Clients'">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Clients</h1>
            <p class="mt-1 text-sm text-gray-500">People you're working with.</p>
        </div>

        <a href="{{ route('agent.clients.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            Add Client
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
        @if ($clients->isEmpty())
            <p class="px-5 py-8 text-center text-sm text-gray-500">No clients yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                            <th class="px-5 py-3 font-medium">Name</th>
                            <th class="px-5 py-3 font-medium">Type</th>
                            <th class="px-5 py-3 font-medium">Lead Status</th>
                            <th class="px-5 py-3 font-medium">Contact</th>
                            <th class="px-5 py-3 font-medium">Client Since</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('agent.clients.show', $client) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                        {{ $client->f_name }} {{ $client->l_name }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-gray-700">{{ $client->type }}</td>
                                <td class="px-5 py-3">
                                    <x-agent.pill :color="$leadColors[$client->lead_status] ?? 'gray'">
                                        {{ $client->lead_status }}
                                    </x-agent.pill>
                                </td>
                                <td class="px-5 py-3 text-gray-700">
                                    <div>{{ $client->email ?? '—' }}</div>
                                    <div class="text-xs text-gray-400">{{ $client->phone ?? '' }}</div>
                                </td>
                                <td class="px-5 py-3 text-gray-500">
                                    {{ $client->client_since?->format('M j, Y') ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-agent-layout>
