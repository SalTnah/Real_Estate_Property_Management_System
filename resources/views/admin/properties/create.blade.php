<x-agent-layout :title="'Add Property'">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Add Property</h1>
        <p class="mt-1 text-sm text-gray-500">Create a new listing on behalf of an agent.</p>
    </div>

    <form method="POST" action="{{ route('admin.properties.store') }}" enctype="multipart/form-data" class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:col-span-2">
            <div>
                <x-input-label for="agent_id" value="Listing agent" />
                <select id="agent_id" name="agent_id" required class="block w-full rounded-lg border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select an agent&hellip;</option>
                    @foreach ($agents as $agent)
                        <option value="{{ $agent->id }}" @selected(old('agent_id') == $agent->id)>{{ $agent->f_name }} {{ $agent->l_name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('agent_id')" />
            </div>

            <div class="mt-6 border-t border-gray-100 pt-6">
                @include('properties._form', ['property' => null])
            </div>
        </div>

        <div class="space-y-4">
            @include('properties._photos_panel', ['property' => null, 'routePrefix' => 'admin'])

            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                Save Property
            </button>
            <a href="{{ route('admin.properties.index') }}" class="block text-center text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</x-agent-layout>
