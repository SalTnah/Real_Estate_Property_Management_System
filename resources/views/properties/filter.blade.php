@php
    $currentStatuses = (array) request('status', []);
    $currentType = request('type');
    $currentBeds = request('beds', 'Any');
    $bedOptions = ['Any', '1+', '2+', '3+', '4+'];
@endphp

<x-agent-layout :title="'Filter & Sort'">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Filter &amp; Sort</h1>
        <p class="mt-1 text-sm text-gray-500">Refine your property list.</p>
    </div>

    <form method="GET" action="{{ route('properties.index') }}" class="mt-6 max-w-xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div>
            <x-input-label for="sort" value="Sort by" />
            <select id="sort" name="sort" class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest first</option>
                <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                <option value="beds" @selected(request('sort') === 'beds')>Most Bedrooms</option>
            </select>
        </div>

        <div class="mt-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Status</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach (['Available' => 'green', 'Pending' => 'amber', 'Sold' => 'red'] as $status => $color)
                    @php
                        $checked = in_array($status, $currentStatuses, true);
                        $activeClasses = [
                            'green' => 'bg-green-500 text-white',
                            'amber' => 'bg-amber-500 text-white',
                            'red' => 'bg-red-500 text-white',
                        ][$color];
                    @endphp
                    <label class="cursor-pointer">
                        <input type="checkbox" name="status[]" value="{{ $status }}" class="peer sr-only" @checked($checked)>
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3.5 py-1.5 text-sm font-medium text-gray-500 peer-checked:{{ $activeClasses }}">
                            {{ $status }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Property Type</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach ($types as $type)
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="{{ $type }}" class="peer sr-only" @checked($currentType === $type)>
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3.5 py-1.5 text-sm font-medium text-gray-500 peer-checked:bg-blue-600 peer-checked:text-white">
                            {{ $type }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="min_price" value="Min" />
                <x-text-input id="min_price" name="min_price" type="number" placeholder="$200,000" :value="request('min_price')" />
            </div>
            <div>
                <x-input-label for="max_price" value="Max" />
                <x-text-input id="max_price" name="max_price" type="number" placeholder="$1,500,000" :value="request('max_price')" />
            </div>
        </div>

        <div class="mt-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Bedrooms</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach ($bedOptions as $option)
                    <label class="cursor-pointer">
                        <input type="radio" name="beds" value="{{ $option }}" class="peer sr-only" @checked($currentBeds === $option)>
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3.5 py-1.5 text-sm font-medium text-gray-500 peer-checked:bg-blue-600 peer-checked:text-white">
                            {{ $option }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                Apply Filters
            </button>
            <a href="{{ route('properties.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Reset</a>
        </div>
    </form>
</x-agent-layout>
