@php
    $recent = $searches->filter(fn ($s) => filled($s->query_term) || filled($s->location) || filled($s->type));
@endphp

<x-agent-layout :title="'Search'">
    <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 to-slate-950 px-6 py-12 text-center sm:px-10">
        <h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Find the right property</h1>
        <p class="mt-2 text-sm text-slate-300">
            Search by keyword, location, or type across {{ number_format($activeCount) }} active listings.
        </p>

        <form method="POST" action="{{ route('search.store') }}" class="mx-auto mt-6 flex max-w-xl items-center gap-2 rounded-full bg-white p-1.5 pl-4 shadow-lg">
            @csrf
            <x-agent.icon name="search" class="h-4 w-4 shrink-0 text-gray-400" />
            <input
                type="text"
                name="query_term"
                placeholder='Try &quot;2-bed condo in Seattle under $600k&quot;'
                class="w-full border-0 bg-transparent p-0 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-0"
            >
            <button type="submit" class="shrink-0 rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                Search
            </button>
        </form>

        <div class="mt-4 flex flex-wrap justify-center gap-2">
            @foreach ($recent as $s)
                <form method="POST" action="{{ route('search.store') }}">
                    @csrf
                    <input type="hidden" name="query_term" value="{{ $s->query_term }}">
                    <input type="hidden" name="location" value="{{ $s->location }}">
                    <input type="hidden" name="type" value="{{ $s->type }}">
                    <button type="submit" class="rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-medium text-white transition hover:bg-white/20">
                        {{ trim($s->query_term ?: trim(($s->type ?? '') . ' ' . $s->location)) ?: 'Recent search' }}
                    </button>
                </form>
            @endforeach
            <form method="POST" action="{{ route('search.store') }}">
                @csrf
                <input type="hidden" name="map_view" value="1">
                <button type="submit" class="rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-medium text-white transition hover:bg-white/20">
                    Map view
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 flex items-center justify-between">
        <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Browse by type</h2>
        <a href="{{ route('saved-searches.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
            Saved searches &rarr;
        </a>
    </div>

    <div class="mt-3 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
        @foreach ($typeCounts as $type => $count)
            <form method="POST" action="{{ route('search.store') }}">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="flex w-full flex-col items-center gap-2 rounded-xl bg-white p-5 text-center shadow-sm ring-1 ring-gray-100 transition hover:shadow-md">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <x-agent.icon name="building" class="h-4.5 w-4.5" />
                    </span>
                    <span class="block font-semibold text-gray-900">{{ $type }}</span>
                    <span class="block text-xs text-gray-400">{{ $count }} listing{{ $count === 1 ? '' : 's' }}</span>
                </button>
            </form>
        @endforeach
    </div>
</x-agent-layout>
