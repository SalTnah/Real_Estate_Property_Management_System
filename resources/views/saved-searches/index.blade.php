<x-agent-layout :title="'Saved Searches'">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Saved Searches</h1>
            <p class="mt-1 text-sm text-gray-500">Get notified when new matches hit the market.</p>
        </div>
        <a href="{{ route('search.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
            <x-agent.icon name="plus" class="h-4 w-4" />
            New Search
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
        @if ($savedSearches->isEmpty())
            <p class="px-5 py-8 text-center text-sm text-gray-500">
                No saved searches yet. Save a search from the
                <a href="{{ route('search.index') }}" class="font-medium text-blue-600 hover:text-blue-700">Search</a> page to start tracking new matches for a client.
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                            <th class="px-5 py-3 font-medium">Name</th>
                            <th class="px-5 py-3 font-medium">Client</th>
                            <th class="px-5 py-3 font-medium">Criteria</th>
                            <th class="px-5 py-3 font-medium">Alerts</th>
                            <th class="px-5 py-3 font-medium">New</th>
                            <th class="px-5 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($savedSearches as $saved)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-gray-900">{{ $saved->search_name }}</td>
                                <td class="px-5 py-3 text-gray-700">
                                    @if ($saved->client)
                                        <a href="{{ route('clients.show', $saved->client) }}" class="hover:text-blue-600">
                                            {{ $saved->client->f_name }} {{ $saved->client->l_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-gray-500">{{ $saved->criteria_summary ?: '—' }}</td>
                                <td class="px-5 py-3">
                                    <form method="POST" action="{{ route('saved-searches.update', $saved) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="search_name" value="{{ $saved->search_name }}">
                                        <input type="hidden" name="criteria_summary" value="{{ $saved->criteria_summary }}">
                                        <input type="hidden" name="alert_frequency" value="{{ $saved->alert_frequency }}">
                                        <input type="hidden" name="alerts_enabled" value="{{ $saved->alerts_enabled ? '0' : '1' }}">
                                        <button type="submit">
                                            <x-agent.pill :color="$saved->alerts_enabled ? 'green' : 'gray'">
                                                {{ $saved->alerts_enabled ? 'On' : 'Off' }}
                                            </x-agent.pill>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-3">
                                    <x-agent.pill :color="$saved->new_matches_count > 0 ? 'blue' : 'gray'">
                                        {{ $saved->new_matches_count }} new
                                    </x-agent.pill>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <form method="POST" action="{{ route('saved-searches.destroy', $saved) }}" onsubmit="return confirm('Remove this saved search?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600">
                                            <x-agent.icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-agent-layout>
