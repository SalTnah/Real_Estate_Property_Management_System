@php
    $routePrefix = $routePrefix ?? 'agent';
    $photos = $property->photos;
    $cover = $photos->first();
    $thumbnails = $photos->skip(1)->take(3);
    $remaining = $photos->count() - 4;
@endphp

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

            <a href="{{ route($routePrefix.'.properties.photos.index', $property) }}" class="flex aspect-[4/3] items-center justify-center rounded-lg bg-slate-900 text-sm font-semibold text-white hover:bg-slate-800">
                @if ($remaining > 0)
                    +{{ $remaining }} photos
                @else
                    Manage photos
                @endif
            </a>
        </div>
    @else
        <a href="{{ route($routePrefix.'.properties.photos.index', $property) }}" class="inline-block text-sm font-medium text-blue-600 hover:text-blue-700">
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
