@php
    $cover = $property->photos->first();
@endphp

<div class="group overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100 transition hover:shadow-md">
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-gray-100">
        <a href="{{ route('properties.show', $property) }}" class="block h-full w-full">
            @if ($cover)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($cover->photo_url) }}" alt="{{ $property->title }}" class="h-full w-full object-cover transition duration-200 group-hover:scale-105">
            @else
                <div class="flex h-full w-full items-center justify-center text-gray-300">
                    <x-agent.icon name="building" class="h-10 w-10" />
                </div>
            @endif
        </a>

        <span class="pointer-events-none absolute left-3 top-3">
            <x-agent.status-badge :status="$property->status" class="bg-white/90 shadow-sm" />
        </span>

        <form method="POST" action="{{ route('properties.favorite', $property) }}" class="absolute right-3 top-3">
            @csrf
            @method('PATCH')
            <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-red-500 shadow-sm hover:bg-white">
                @if ($property->is_favorited)
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M12 21s-7.5-4.6-10-9.1C.4 8.7 2 5 5.5 5c2 0 3.4 1 4.5 2.4C11.1 6 12.5 5 14.5 5 18 5 19.6 8.7 22 11.9 19.5 16.4 12 21 12 21z"/></svg>
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 21s-7.5-4.6-10-9.1C.4 8.7 2 5 5.5 5c2 0 3.4 1 4.5 2.4C11.1 6 12.5 5 14.5 5 18 5 19.6 8.7 22 11.9 19.5 16.4 12 21 12 21z"/></svg>
                @endif
            </button>
        </form>
    </div>

    <div class="p-4">
        <p class="text-lg font-bold text-gray-900">${{ number_format($property->price) }}</p>
        <a href="{{ route('properties.show', $property) }}" class="mt-0.5 block font-medium text-gray-900 hover:text-blue-600">{{ $property->title }}</a>
        <p class="mt-0.5 flex items-center gap-1 text-sm text-gray-500">
            <x-agent.icon name="map-pin" class="h-3.5 w-3.5 shrink-0" />
            {{ $property->street_address }}, {{ $property->city }}, {{ $property->state }}
        </p>

        <div class="mt-3 flex items-center gap-4 border-t border-gray-100 pt-3 text-sm text-gray-500">
            <span>{{ $property->bedrooms ?? '—' }} bd</span>
            <span>{{ $property->bathrooms ?? '—' }} ba</span>
            <span>{{ $property->size_sqft ? number_format($property->size_sqft) : '—' }} sqft</span>
        </div>
    </div>
</div>
