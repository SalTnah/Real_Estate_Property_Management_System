@php
    $photos = $property->photos;
    $cover = $photos->first();
    $thumbnails = $photos->skip(1)->take(3);
    $remaining = $photos->count() - 4;
@endphp

<x-agent-layout :title="$property->title">
    <nav class="text-sm text-gray-500">
        <a href="{{ route("{$routePrefix}.properties.index") }}" class="text-blue-600 hover:text-blue-700">Properties</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700">{{ $property->title }}</span>
    </nav>

    <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <div class="relative aspect-[16/10] w-full overflow-hidden rounded-xl bg-gray-100">
                @if ($cover)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($cover->photo_url) }}" alt="{{ $property->title }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full w-full items-center justify-center text-gray-300">
                        <x-agent.icon name="building" class="h-14 w-14" />
                    </div>
                @endif
                <span class="absolute left-3 top-3">
                    <x-agent.status-badge :status="$property->status" class="bg-white/90 shadow-sm" />
                </span>
            </div>

            @if ($photos->count() > 1)
                <div class="grid grid-cols-4 gap-3">
                    @foreach ($thumbnails as $thumb)
                        <div class="aspect-[4/3] overflow-hidden rounded-lg bg-gray-100">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($thumb->photo_url) }}" class="h-full w-full object-cover">
                        </div>
                    @endforeach

                    <a href="{{ route("{$routePrefix}.properties.photos.index", $property) }}" class="flex aspect-[4/3] items-center justify-center rounded-lg bg-slate-900 text-sm font-semibold text-white hover:bg-slate-800">
                        @if ($remaining > 0)
                            +{{ $remaining }} photos
                        @else
                            Manage photos
                        @endif
                    </a>
                </div>
            @else
                <a href="{{ route("{$routePrefix}.properties.photos.index", $property) }}" class="inline-block text-sm font-medium text-blue-600 hover:text-blue-700">
                    + Add more photos
                </a>
            @endif

            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <h2 class="text-base font-semibold text-gray-900">Description</h2>
                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">
                    {{ $property->description ?? 'No description added yet.' }}
                </p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <p class="text-2xl font-bold text-gray-900">${{ number_format($property->price) }}</p>
                <p class="mt-1 font-medium text-gray-900">{{ $property->title }}</p>
                <p class="text-sm text-gray-500">{{ $property->street_address }}, {{ $property->city }}, {{ $property->state }} {{ $property->zip }}</p>

                <div class="mt-4 grid grid-cols-2 gap-2">
                    <span class="inline-flex cursor-not-allowed items-center justify-center rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-400" title="Coming soon — Appointments module">
                        Book Viewing
                    </span>
                    <a href="{{ route("{$routePrefix}.properties.edit", $property) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Edit
                    </a>
                </div>
                <form method="POST" action="{{ route("{$routePrefix}.properties.destroy", $property) }}" class="mt-2" onsubmit="return confirm('Delete this property? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">
                        Delete
                    </button>
                </form>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Details</h2>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="text-sm font-medium text-blue-600 hover:text-blue-700">Change status</button>
                        <div x-show="open" x-cloak x-transition class="absolute right-0 z-20 mt-2 w-40 rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                            @foreach (\App\Http\Controllers\PropertyController::STATUSES as $status)
                                <form method="POST" action="{{ route("{$routePrefix}.properties.status", $property) }}">
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

            @if ($property->agent)
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Listing Agent</p>
                    <div class="mt-3 flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                            {{ strtoupper(substr($property->agent->f_name, 0, 1).substr($property->agent->l_name, 0, 1)) }}
                        </span>
                        <div>
                            <p class="font-medium text-gray-900">{{ $property->agent->f_name }} {{ $property->agent->l_name }}</p>
                            <p class="text-sm text-gray-500">{{ $property->agent->agency_name }}{{ $property->agent->phone_num ? ' · '.$property->agent->phone_num : '' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-agent-layout>