<x-agent-layout :title="'Add Property'">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Add Property</h1>
        <p class="mt-1 text-sm text-gray-500">Create a new listing for your portfolio.</p>
    </div>

    <form method="POST" action="{{ route('agent.properties.store') }}" enctype="multipart/form-data" class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:col-span-2">
            @include('properties._form', ['property' => null])
        </div>

        <div class="space-y-4">
            @include('properties._photos_panel', ['property' => null, 'routePrefix' => 'agent'])

            <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                Save Property
            </button>
            <a href="{{ route('agent.properties.index') }}" class="block text-center text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</x-agent-layout>
