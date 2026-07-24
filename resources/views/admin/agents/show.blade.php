<x-agent-layout :title="$agent->f_name.' '.$agent->l_name">
    <nav class="text-sm text-gray-500">
        <a href="{{ route('admin.agents.index') }}" class="text-blue-600 hover:text-blue-700">Agents</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700">{{ $agent->f_name }} {{ $agent->l_name }}</span>
    </nav>

    <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:col-span-1">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-lg font-semibold text-white">
                {{ strtoupper(substr($agent->f_name, 0, 1).substr($agent->l_name, 0, 1)) }}
            </div>
            <h1 class="mt-4 text-lg font-bold text-gray-900">{{ $agent->f_name }} {{ $agent->l_name }}</h1>
            <p class="text-sm text-gray-500">{{ $agent->agency_name ?? 'No agency on file' }}</p>

            <dl class="mt-5 space-y-3 border-t border-gray-100 pt-5 text-sm">
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
                <p class="mt-4 border-t border-gray-100 pt-4 text-sm text-gray-600">{{ $agent->bio }}</p>
            @endif

            <a href="{{ route('admin.agents.edit', $agent) }}" class="mt-5 block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Edit Agent
            </a>
            <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" class="mt-2" onsubmit="return confirm('Remove this agent account? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="block w-full rounded-lg border border-red-200 px-4 py-2.5 text-center text-sm font-semibold text-red-600 hover:bg-red-50">
                    Remove Agent
                </button>
            </form>
        </div>

        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="px-5 py-4">
                    <h2 class="text-base font-semibold text-gray-900">Clients ({{ $agent->clients->count() }})</h2>
                </div>
                @if ($agent->clients->isEmpty())
                    <p class="px-5 pb-5 text-sm text-gray-500">No clients assigned yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-y border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                                    <th class="px-5 py-2.5 font-medium">Name</th>
                                    <th class="px-5 py-2.5 font-medium">Type</th>
                                    <th class="px-5 py-2.5 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agent->clients as $client)
                                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                        <td class="px-5 py-3">
                                            <a href="{{ route('clients.show', $client) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                                {{ $client->f_name }} {{ $client->l_name }}
                                            </a>
                                        </td>
                                        <td class="px-5 py-3 text-gray-700">{{ $client->type }}</td>
                                        <td class="px-5 py-3 text-gray-700">{{ $client->lead_status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="px-5 py-4">
                    <h2 class="text-base font-semibold text-gray-900">Properties ({{ $agent->properties->count() }})</h2>
                </div>
                @if ($agent->properties->isEmpty())
                    <p class="px-5 pb-5 text-sm text-gray-500">No properties listed yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-y border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                                    <th class="px-5 py-2.5 font-medium">Property</th>
                                    <th class="px-5 py-2.5 font-medium">Price</th>
                                    <th class="px-5 py-2.5 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agent->properties as $property)
                                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                        <td class="px-5 py-3">
                                            <a href="{{ route('properties.show', $property) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                                {{ $property->title }}
                                            </a>
                                        </td>
                                        <td class="px-5 py-3 text-gray-700">${{ number_format($property->price) }}</td>
                                        <td class="px-5 py-3"><x-agent.status-badge :status="$property->status" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-agent-layout>
