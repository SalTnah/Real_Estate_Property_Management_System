@php
    $criteria = $criteria ?? [];
    $initialView = ! empty($criteria['map_view']) ? 'map' : 'list';
@endphp

<x-agent-layout :title="'Search Results'">
    <div x-data="{ view: '{{ $initialView }}', filtersOpen: true }">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex min-w-0 flex-1 items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2.5">
                <x-agent.icon name="search" class="h-4 w-4 shrink-0 text-gray-400" />
                <span class="truncate text-sm text-gray-700">{{ $criteria['query_term'] ?? '' ?: 'All properties' }}</span>
            </div>
            <button type="button" @click="filtersOpen = !filtersOpen" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                <x-agent.icon name="filter" class="h-4 w-4" />
                Filters
            </button>
            <div class="ml-auto flex items-center gap-2">
                <button type="button" @click="view = (view === 'map' ? 'list' : 'map')" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span x-text="view === 'map' ? 'List view' : 'Map view'"></span>
                </button>
                <button type="button" x-on:click.prevent="$dispatch('open-modal', 'save-search')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Save search
                </button>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 items-start gap-5" :class="filtersOpen ? 'lg:grid-cols-[240px_1fr]' : ''">
            <div x-show="filtersOpen" x-cloak class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                <p class="text-sm font-bold text-gray-900">Filters</p>
                <form method="POST" action="{{ route('search.store') }}" class="mt-4 space-y-5">
                    @csrf
                    <input type="hidden" name="query_term" value="{{ $criteria['query_term'] ?? '' }}">
                    <div>
                        <x-input-label for="location" value="Location" />
                        <x-text-input id="location" name="location" class="mt-1 block w-full" :value="$criteria['location'] ?? ''" placeholder="Seattle, WA" />
                    </div>
                    <div>
                        <x-input-label for="type" value="Type" />
                        <select id="type" name="type" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Any</option>
                            @foreach (\App\Http\Controllers\SearchController::TYPES as $type)
                                <option value="{{ $type }}" @selected(($criteria['type'] ?? '') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Price</p>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <x-text-input name="price_min" type="number" class="block w-full" placeholder="Min" :value="$criteria['price_min'] ?? ''" />
                            <x-text-input name="price_max" type="number" class="block w-full" placeholder="Max" :value="$criteria['price_max'] ?? ''" />
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Beds</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach (['' => 'Any', '2' => '2+', '3' => '3+', '4' => '4+'] as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="beds" value="{{ $value }}" class="peer sr-only" @checked(($criteria['beds'] ?? '') == $value)>
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-500 peer-checked:bg-blue-600 peer-checked:text-white">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <x-input-label for="sort" value="Sort" />
                        <select id="sort" name="sort" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="" @selected(empty($criteria['sort']))>Relevance</option>
                            <option value="price_asc" @selected(($criteria['sort'] ?? '') === 'price_asc')>Price: Low to High</option>
                            <option value="price_desc" @selected(($criteria['sort'] ?? '') === 'price_desc')>Price: High to Low</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                        Apply
                    </button>
                </form>
            </div>

            <div class="min-w-0">
                <p class="text-sm text-gray-500">{{ $results->count() }} result{{ $results->count() === 1 ? '' : 's' }}</p>

                @if ($results->isEmpty())
                    <div class="mt-4 rounded-xl bg-white p-10 text-center shadow-sm ring-1 ring-gray-100">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                            <x-agent.icon name="search" class="h-6 w-6" />
                        </div>
                        <h3 class="mt-3 font-semibold text-gray-900">No properties match your search</h3>
                        <p class="mt-1 text-sm text-gray-500">Try widening your price range or removing a filter.</p>
                        <div class="mt-4 flex justify-center gap-3">
                            <a href="{{ route('search.index') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Start over</a>
                            <button type="button" x-on:click.prevent="$dispatch('open-modal', 'save-search')" class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Save &amp; notify me
                            </button>
                        </div>
                    </div>
                @else
                    <div x-show="view === 'list'" class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        @foreach ($results as $property)
                            @include('properties._card', ['property' => $property])
                        @endforeach
                    </div>

                    <div x-show="view === 'map'" x-cloak class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-[300px_1fr]">
                        <div class="flex max-h-[560px] flex-col gap-3 overflow-y-auto">
                            @foreach ($results as $property)
                                <a href="{{ route('properties.show', $property) }}" class="flex gap-3 rounded-xl bg-white p-3 shadow-sm ring-1 ring-gray-100 hover:shadow-md">
                                    <div class="h-16 w-20 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                        @if ($property->photos->first())
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($property->photos->first()->photo_url) }}" class="h-full w-full object-cover" alt="">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900">${{ number_format($property->price) }}</p>
                                        <p class="truncate text-xs text-gray-500">{{ $property->title }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="relative min-h-[400px] overflow-hidden rounded-xl bg-blue-50 ring-1 ring-gray-100">
                            <div class="absolute inset-0" style="background-image: radial-gradient(circle, #cbd5e1 1px, transparent 1px); background-size: 22px 22px;"></div>
                            {{-- Pins are placed with a lightweight deterministic offset for now. Swap this loop for a real
                                 lat/lng-to-pixel projection (e.g. Leaflet + OpenStreetMap) using $property->latitude/longitude. --}}
                            @foreach ($results as $i => $property)
                                @php
                                    $left = 15 + (($i * 37) % 70);
                                    $top = 15 + (($i * 53) % 65);
                                @endphp
                                <a href="{{ route('properties.show', $property) }}" style="left: {{ $left }}%; top: {{ $top }}%;" class="absolute -translate-x-1/2 -translate-y-full rounded-full bg-slate-900 px-2.5 py-1 text-xs font-bold text-white shadow-md hover:bg-blue-600">
                                    ${{ number_format($property->price / 1000) }}k
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-modal name="save-search" focusable>
        <form method="POST" action="{{ route('saved-searches.store') }}" class="p-6">
            @csrf
            <h3 class="text-lg font-bold text-gray-900">Save this search</h3>
            <p class="mt-1 text-sm text-gray-500">We'll alert you when new matching properties are listed.</p>

            <div class="mt-4 space-y-4">
                <div>
                    <x-input-label for="ss_client" value="Client" />
                    <select id="ss_client" name="client_id" required class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select a client&hellip;</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->f_name }} {{ $client->l_name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Saved searches track new matches for a specific client.</p>
                </div>
                <div>
                    <x-input-label for="ss_name" value="Search name" />
                    <x-text-input id="ss_name" name="search_name" class="mt-1 block w-full" required :value="($criteria['type'] ?? '') . (($criteria['type'] ?? '') && ($criteria['location'] ?? '') ? ' in ' : '') . ($criteria['location'] ?? '')" />
                </div>
                <input type="hidden" name="criteria_summary" value="{{ collect([$criteria['type'] ?? null, $criteria['location'] ?? null])->filter()->join(' / ') }}">
                <div>
                    <x-input-label for="ss_freq" value="Alert frequency" />
                    <select id="ss_freq" name="alert_frequency" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="Instantly">Instantly</option>
                        <option value="Daily">Daily digest</option>
                        <option value="Weekly">Weekly digest</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'save-search')" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</button>
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save Search</button>
            </div>
        </form>
    </x-modal>
</x-agent-layout>
