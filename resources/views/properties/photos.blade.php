@php($routePrefix = $routePrefix ?? 'agent')
<x-agent-layout :title="'Update Images'">
    <nav class="text-sm text-gray-500">
        <a href="{{ route($routePrefix.'.properties.show', $property) }}" class="text-blue-600 hover:text-blue-700">{{ $property->title }}</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700">Update Images</span>
    </nav>

    <div class="mt-4 max-w-3xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h1 class="text-xl font-bold text-gray-900">Update Property Images</h1>
        <p class="mt-1 text-sm text-gray-500">Reorder photos below — the first image is the cover.</p>

        <form method="POST" action="{{ route($routePrefix.'.properties.photos.store', $property) }}" enctype="multipart/form-data" class="mt-5">
            @csrf
            <label for="photos" class="flex h-32 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 text-sm text-gray-500 hover:bg-gray-100">
                <x-agent.icon name="camera" class="h-5 w-5 text-gray-400" />
                <span class="mt-1">Drag &amp; drop images here, or click to browse</span>
                <input id="photos" name="photos[]" type="file" accept="image/*" multiple class="hidden" onchange="this.form.submit()">
            </label>
            <x-input-error :messages="$errors->get('photos')" class="mt-2" />
        </form>

        @if ($property->photos->isNotEmpty())
            <div x-data="{
                photos: {{ \Illuminate\Support\Js::from($property->photos->map(fn ($p) => [
                    'id' => $p->id,
                    'url' => \Illuminate\Support\Facades\Storage::url($p->photo_url),
                ])->values()) }},
                moveUp(i) { if (i > 0) { [this.photos[i - 1], this.photos[i]] = [this.photos[i], this.photos[i - 1]]; } },
                moveDown(i) { if (i < this.photos.length - 1) { [this.photos[i + 1], this.photos[i]] = [this.photos[i], this.photos[i + 1]]; } },
            }" class="mt-6">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400" x-text="'Uploaded (' + photos.length + ')'"></p>

                <form method="POST" action="{{ route($routePrefix.'.properties.photos.reorder', $property) }}" class="mt-3">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <template x-for="(photo, index) in photos" :key="photo.id">
                            <div class="relative">
                                <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
                                    <img :src="photo.url" class="h-full w-full object-cover">
                                </div>
                                <span x-show="index === 0" class="absolute left-1.5 top-1.5 rounded-full bg-white/90 px-2 py-0.5 text-xs font-medium text-gray-700 shadow-sm">Cover</span>

                                <div class="mt-1.5 flex items-center justify-between">
                                    <div class="flex gap-1">
                                        <button type="button" @click="moveUp(index)" class="rounded border border-gray-200 px-1.5 py-0.5 text-xs text-gray-500 hover:bg-gray-50">&larr;</button>
                                        <button type="button" @click="moveDown(index)" class="rounded border border-gray-200 px-1.5 py-0.5 text-xs text-gray-500 hover:bg-gray-50">&rarr;</button>
                                    </div>
                                </div>

                                <input type="hidden" name="order[]" :value="photo.id">
                            </div>
                        </template>
                    </div>

                    <div class="mt-5 flex items-center gap-3">
                        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                            Save &amp; Done
                        </button>
                        <a href="{{ route($routePrefix.'.properties.show', $property) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
                    </div>
                </form>

                <div class="mt-5 border-t border-gray-100 pt-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Remove a photo</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($property->photos as $photo)
                            <form method="POST" action="{{ route($routePrefix.'.properties.photos.destroy', [$property, $photo->id]) }}" onsubmit="return confirm('Remove this photo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-full border border-red-200 px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50">
                                    Remove photo #{{ $loop->iteration }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <p class="mt-6 text-sm text-gray-500">No photos uploaded yet.</p>
        @endif
    </div>
</x-agent-layout>
