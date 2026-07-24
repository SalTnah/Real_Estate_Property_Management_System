<x-agent-layout :title="'Update Images'">
    <nav class="text-sm text-gray-500">
        <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:text-blue-700">{{ $property->title }}</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700">Update Images</span>
    </nav>

    {{-- Upload card (matches "prop-image-upload" screen) --}}
    <div class="mt-4 max-w-3xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <h1 class="text-xl font-bold text-gray-900">Add Photos</h1>
        <p class="mt-1 text-sm text-gray-500">Upload high-quality photos of the property. JPG or PNG, up to 10MB each.</p>

        <form method="POST" action="{{ route('properties.photos.store', $property) }}" enctype="multipart/form-data" class="mt-5">
            @csrf
            <label
                for="photos"
                x-data="{ dragging: false }"
                x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="dragging = false; $refs.photosInput.files = $event.dataTransfer.files; $refs.photosInput.form.submit();"
                :class="dragging ? 'border-blue-400 bg-blue-50' : 'border-gray-200 bg-gray-50 hover:bg-gray-100'"
                class="flex h-44 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed text-center transition"
            >
                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-gray-100">
                    <x-agent.icon name="camera" class="h-5 w-5 text-gray-400" />
                </span>
                <span class="mt-3 text-sm font-medium text-gray-700">Drag &amp; drop images here</span>
                <span class="mt-0.5 text-sm text-gray-400">or <span class="font-medium text-blue-600">click to browse</span></span>
                <input x-ref="photosInput" id="photos" name="photos[]" type="file" accept="image/*" multiple class="hidden" onchange="this.form.submit()">
            </label>
            <x-input-error :messages="$errors->get('photos')" class="mt-2" />
            <x-input-error :messages="$errors->get('photos.*')" class="mt-2" />
        </form>
    </div>

    {{-- Manage / reorder card (matches "prop-image-edit" screen) --}}
    <div
        x-data="{
            photos: {{ \Illuminate\Support\Facades\Js::from($property->photos->map(fn ($p) => [
                'id' => $p->id,
                'url' => \Illuminate\Support\Facades\Storage::url($p->photo_url),
            ])->values()) }},
            dragIndex: null,
            startDrag(i) { this.dragIndex = i; },
            onDrop(i) {
                if (this.dragIndex === null || this.dragIndex === i) return;
                const item = this.photos.splice(this.dragIndex, 1)[0];
                this.photos.splice(i, 0, item);
                this.dragIndex = null;
            },
            removePhoto(id, index) {
                if (! confirm('Remove this photo?')) return;
                fetch(`{{ route('properties.photos.destroy', [$property, '__ID__']) }}`.replace('__ID__', id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-HTTP-Method-Override': 'DELETE',
                    },
                }).then(() => this.photos.splice(index, 1));
            },
        }"
        class="mt-6 max-w-3xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100"
    >
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Manage Photos</h2>
                <p class="mt-1 text-sm text-gray-500">Drag to reorder — the first image is the cover.</p>
            </div>
            <span class="text-sm font-medium text-gray-400" x-text="photos.length + ' photo' + (photos.length === 1 ? '' : 's')"></span>
        </div>

        <template x-if="photos.length === 0">
            <p class="mt-6 text-sm text-gray-500">No photos uploaded yet.</p>
        </template>

        <form method="POST" action="{{ route('properties.photos.reorder', $property) }}" class="mt-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <template x-for="(photo, index) in photos" :key="photo.id">
                    <div
                        class="group relative"
                        draggable="true"
                        x-on:dragstart="startDrag(index)"
                        x-on:dragover.prevent
                        x-on:drop.prevent="onDrop(index)"
                    >
                        <div class="aspect-square cursor-move overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-100">
                            <img :src="photo.url" class="h-full w-full object-cover">
                        </div>

                        <span x-show="index === 0" class="pointer-events-none absolute left-2 top-2 rounded-full bg-white/95 px-2 py-0.5 text-xs font-semibold text-gray-700 shadow-sm">
                            Cover
                        </span>

                        <button
                            type="button"
                            @click="removePhoto(photo.id, index)"
                            class="absolute right-2 top-2 flex h-6 w-6 items-center justify-center rounded-full bg-white/95 text-gray-500 opacity-0 shadow-sm transition hover:bg-red-50 hover:text-red-600 group-hover:opacity-100"
                            title="Remove photo"
                        >
                            <x-agent.icon name="x" class="h-3.5 w-3.5" />
                        </button>

                        <input type="hidden" name="order[]" :value="photo.id">
                    </div>
                </template>

                <label for="photos" class="flex aspect-square cursor-pointer flex-col items-center justify-center gap-1 rounded-lg border-2 border-dashed border-gray-200 text-gray-400 hover:bg-gray-50 hover:text-gray-500">
                    <x-agent.icon name="plus" class="h-5 w-5" />
                    <span class="text-xs font-medium">Add more</span>
                </label>
            </div>

            <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Save &amp; Done
                </button>
                <a href="{{ route('properties.show', $property) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</x-agent-layout>