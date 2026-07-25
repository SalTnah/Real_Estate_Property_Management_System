@php
    $routePrefix = $routePrefix ?? 'agent';
    $statuses = $statuses ?? [];
@endphp

<div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
    <div class="flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-900">Details</h2>
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.outside="open = false" class="text-sm font-medium text-blue-600 hover:text-blue-700">Change status</button>
            <div x-show="open" x-cloak x-transition class="absolute right-0 z-20 mt-2 w-40 rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                @foreach ($statuses as $status)
                    <form method="POST" action="{{ route($routePrefix.'.properties.status', $property) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status }}">
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">{{ $status }}</button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <dl class="mt-4 divide-y divide-gray-100 text-sm">
        <div class="flex justify-between py-2">
            <dt class="text-gray-400">Status</dt>
            <dd><x-agent.status-badge :status="$property->status" /></dd>
        </div>
        <div class="flex justify-between py-2">
            <dt class="text-gray-400">Type</dt>
            <dd class="font-medium text-gray-900">{{ $property->property_type }}</dd>
        </div>
        <div class="flex justify-between py-2">
            <dt class="text-gray-400">Bedrooms</dt>
            <dd class="font-medium text-gray-900">{{ $property->bedrooms ?? '—' }}</dd>
        </div>
        <div class="flex justify-between py-2">
            <dt class="text-gray-400">Bathrooms</dt>
            <dd class="font-medium text-gray-900">{{ $property->bathrooms ?? '—' }}</dd>
        </div>
        <div class="flex justify-between py-2">
            <dt class="text-gray-400">Size</dt>
            <dd class="font-medium text-gray-900">{{ $property->size_sqft ? number_format($property->size_sqft).' sqft' : '—' }}</dd>
        </div>
        <div class="flex justify-between py-2">
            <dt class="text-gray-400">Year built</dt>
            <dd class="font-medium text-gray-900">{{ $property->year_built ?? '—' }}</dd>
        </div>
        <div class="flex justify-between py-2">
            <dt class="text-gray-400">Listed</dt>
            <dd class="font-medium text-gray-900">{{ $property->created_at?->format('M j, Y') }}</dd>
        </div>
    </dl>
</div>
