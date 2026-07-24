@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $firstName = $agent->f_name ?? auth()->user()->name;
@endphp

<x-agent-layout :title="'Dashboard'">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $greeting }}, {{ $firstName }}</h1>
            <p class="mt-1 text-sm text-gray-500">Here's what's happening across your portfolio today.</p>
        </div>
        <a href="{{ route('properties.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            Add Property
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                <x-agent.icon name="building" class="h-5 w-5" />
            </span>
            <p class="mt-4 text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
            <p class="mt-1 text-sm text-gray-500">Active Listings</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-600">
                <x-agent.icon name="check-square" class="h-5 w-5" />
            </span>
            <p class="mt-4 text-2xl font-bold text-gray-900">{{ $stats['sold'] }}</p>
            <p class="mt-1 text-sm text-gray-500">Sold (YTD)</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                <x-agent.icon name="users" class="h-5 w-5" />
            </span>
            <p class="mt-4 text-2xl font-bold text-gray-900">{{ $stats['clients'] }}</p>
            <p class="mt-1 text-sm text-gray-500">Total Clients</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                <x-agent.icon name="clock" class="h-5 w-5" />
            </span>
            <p class="mt-4 text-2xl font-bold text-gray-900">{{ $stats['viewingsToday'] }}</p>
            <p class="mt-1 text-sm text-gray-500">Viewings Today</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 lg:col-span-2">
            <div class="flex items-center justify-between px-5 py-4">
                <h2 class="text-base font-semibold text-gray-900">Recent Listings</h2>
                <a href="{{ route('properties.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all &rarr;</a>
            </div>

            @if ($recentProperties->isEmpty())
                <p class="px-5 pb-6 text-sm text-gray-500">No properties yet. Add your first listing to see it here.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-y border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                                <th class="px-5 py-2.5 font-medium">Property</th>
                                <th class="px-5 py-2.5 font-medium">Price</th>
                                <th class="px-5 py-2.5 font-medium">Type</th>
                                <th class="px-5 py-2.5 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentProperties as $property)
                                <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('properties.show', $property) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                            {{ $property->title }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-gray-700">${{ number_format($property->price) }}</td>
                                    <td class="px-5 py-3 text-gray-700">{{ $property->property_type }}</td>
                                    <td class="px-5 py-3"><x-agent.status-badge :status="$property->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
            <div class="px-5 py-4">
                <h2 class="text-base font-semibold text-gray-900">Today's Schedule</h2>
            </div>
            <div class="space-y-4 px-5 pb-5">
                @forelse ($todaySchedule as $appointment)
                    <div>
                        <p class="text-xs text-gray-400">{{ $appointment->start_time->format('g:i A') }}</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $appointment->title }}
                            @if ($appointment->property)
                                &mdash; {{ $appointment->property->title }}
                            @elseif ($appointment->client)
                                &mdash; {{ $appointment->client->f_name }} {{ $appointment->client->l_name }}
                            @endif
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Nothing scheduled for today.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-agent-layout>
