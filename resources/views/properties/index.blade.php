@php
    $quick = request('quick', 'All');
    $chips = ['All', 'Available', 'Pending', 'Sold', 'House', 'Condo', 'Townhouse'];
@endphp

<x-agent-layout :title="'Properties'">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Properties</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $counts['all'] }} listings &middot; {{ $counts['available'] }} available &middot; {{ $counts['pending'] }} pending &middot; {{ $counts['sold'] }} sold
            </p>
        </div>
        <div class="flex shrink-0 items-center gap-3">
            <a href="{{ route("{$routePrefix}.properties.filter") }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <x-agent.icon name="filter" class="h-4 w-4" />
                Filter &amp; Sort
            </a>
            <a href="{{ route("{$routePrefix}.properties.create") }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                <x-agent.icon name="plus" class="h-4 w-4" />
                Add Property
            </a>
        </div>
    </div>

    <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap gap-2">
            @foreach ($chips as $chip)
                @php
                    $isActive = $chip === 'All' ? $quick === 'All' : $quick === $chip;
                    $href = $chip === 'All'
                        ? request()->fullUrlWithQuery(['quick' => null])
                        : request()->fullUrlWithQuery(['quick' => $chip]);
                @endphp
                <a
                    href="{{ $href }}"
                    class="{{ $isActive ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50' }} rounded-full px-3.5 py-1.5 text-sm font-medium transition"
                >
                    {{ $chip }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-1 rounded-lg bg-white p-1 ring-1 ring-gray-200">
            <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="{{ $view === 'grid' ? 'bg-gray-100 text-gray-900' : 'text-gray-400' }} flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium">
                <x-agent.icon name="grid" class="h-4 w-4" /> Grid
            </a>
            <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="{{ $view === 'list' ? 'bg-gray-100 text-gray-900' : 'text-gray-400' }} flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium">
                <x-agent.icon name="list" class="h-4 w-4" /> List
            </a>
        </div>
    </div>

    @if ($properties->isEmpty())
        <div class="mt-8 rounded-xl bg-white p-10 text-center shadow-sm ring-1 ring-gray-100">
            <p class="text-sm text-gray-500">No properties match these filters.</p>
            <a href="{{ route("{$routePrefix}.properties.index") }}" class="mt-2 inline-block text-sm font-medium text-blue-600 hover:text-blue-700">Clear filters</a>
        </div>
    @elseif ($view === 'list')
        <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                            <th class="px-5 py-3 font-medium">Property</th>
                            <th class="px-5 py-3 font-medium">Price</th>
                            <th class="px-5 py-3 font-medium">Type</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Beds/Baths</th>
                            <th class="px-5 py-3 font-medium">Listed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($properties as $property)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route("{$routePrefix}.properties.show", $property) }}" class="font-medium text-gray-900 hover:text-blue-600">{{ $property->title }}</a>
                                    <p class="text-xs text-gray-400">{{ $property->street_address }}, {{ $property->city }}, {{ $property->state }}</p>
                                </td>
                                <td class="px-5 py-3 text-gray-700">${{ number_format($property->price) }}</td>
                                <td class="px-5 py-3 text-gray-700">{{ $property->property_type }}</td>
                                <td class="px-5 py-3"><x-agent.status-badge :status="$property->status" /></td>
                                <td class="px-5 py-3 text-gray-700">{{ $property->bedrooms ?? '—' }} bd / {{ $property->bathrooms ?? '—' }} ba</td>
                                <td class="px-5 py-3 text-gray-500">{{ $property->created_at?->format('M j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($properties as $property)
                @include('properties._card', ['property' => $property])
            @endforeach
        </div>
    @endif

    @if ($properties->hasPages())
        <div class="mt-6">
            {{ $properties->links() }}
        </div>
    @endif
</x-agent-layout>
