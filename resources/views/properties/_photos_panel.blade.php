@php
    $property = $property ?? null;
    $routePrefix = $routePrefix ?? 'agent';
@endphp

<div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Photos</p>

    <label for="photos" class="mt-3 flex h-32 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 text-sm text-gray-500 hover:bg-gray-100">
        <x-agent.icon name="camera" class="h-5 w-5 text-gray-400" />
        <span class="mt-1" x-data="{ count: 0 }" x-on:change="count = $refs.photosInput.files.length" x-text="count > 0 ? count + ' file(s) selected' : 'Upload images'"></span>
        <input x-ref="photosInput" id="photos" name="photos[]" type="file" accept="image/*" multiple class="hidden">
    </label>
    <x-input-error :messages="$errors->get('photos')" class="mt-2" />
    <x-input-error :messages="$errors->get('photos.*')" class="mt-2" />

    @if ($property && $property->photos->isNotEmpty())
        <div class="mt-4 grid grid-cols-3 gap-2">
            @foreach ($property->photos->take(6) as $photo)
                <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
                    <img src="{{ $photo->url }}" class="h-full w-full object-cover">
                </div>
            @endforeach
        </div>
        <a href="{{ route($routePrefix.'.properties.photos.index', $property) }}" class="mt-3 block text-center text-sm font-medium text-blue-600 hover:text-blue-700">
            Manage photos
        </a>
    @endif
</div>