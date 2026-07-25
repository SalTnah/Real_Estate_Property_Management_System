<x-agent-layout :title="$property->title">
    <nav class="text-sm text-gray-500">
        <a href="{{ route('agent.properties.index') }}" class="text-blue-600 hover:text-blue-700">Properties</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700">{{ $property->title }}</span>
    </nav>

    <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">
        @include('properties._gallery_and_description', ['property' => $property, 'routePrefix' => 'agent'])

        <div class="space-y-6">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <p class="text-2xl font-bold text-gray-900">${{ number_format($property->price) }}</p>
                <p class="mt-1 font-medium text-gray-900">{{ $property->title }}</p>
                <p class="text-sm text-gray-500">{{ $property->street_address }}, {{ $property->city }}, {{ $property->state }} {{ $property->zip }}</p>

                <div class="mt-4 grid grid-cols-2 gap-2">
                    <span class="inline-flex cursor-not-allowed items-center justify-center rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-400" title="Coming soon — Appointments module">
                        Book Viewing
                    </span>
                    <a href="{{ route('agent.properties.edit', $property) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Edit
                    </a>
                </div>
                <form method="POST" action="{{ route('agent.properties.destroy', $property) }}" class="mt-2" onsubmit="return confirm('Delete this property? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">
                        Delete
                    </button>
                </form>
            </div>

            @include('properties._details_card', ['property' => $property, 'routePrefix' => 'agent', 'statuses' => $statuses])

            @include('properties._listing_agent_card', ['property' => $property])
        </div>
    </div>
</x-agent-layout>
