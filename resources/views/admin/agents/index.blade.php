<x-agent-layout :title="'Agents'">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Agents</h1>
            <p class="mt-1 text-sm text-gray-500">Create and manage agent accounts.</p>
        </div>
        <a href="{{ route('admin.agents.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            Add Agent
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
        @if ($agents->isEmpty())
            <p class="px-5 py-8 text-center text-sm text-gray-500">No agents yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                            <th class="px-5 py-3 font-medium">Name</th>
                            <th class="px-5 py-3 font-medium">Email</th>
                            <th class="px-5 py-3 font-medium">Agency</th>
                            <th class="px-5 py-3 font-medium">Clients</th>
                            <th class="px-5 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $agent)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.agents.show', $agent) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                        {{ $agent->f_name }} {{ $agent->l_name }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-gray-700">{{ $agent->email }}</td>
                                <td class="px-5 py-3 text-gray-700">{{ $agent->agency_name ?? '—' }}</td>
                                <td class="px-5 py-3 text-gray-700">{{ $agent->clients_count }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.agents.edit', $agent) }}" class="font-medium text-blue-600 hover:text-blue-700">Edit</a>
                                        <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}" onsubmit="return confirm('Remove this agent account? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-700">Remove</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-agent-layout>
