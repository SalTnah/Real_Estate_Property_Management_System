<x-agent-layout :title="'Edit Client'">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Edit Client</h1>
        <p class="mt-1 text-sm text-gray-500">Update details for {{ $client->f_name }} {{ $client->l_name }}.</p>
    </div>

    <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="mt-6 max-w-3xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        @csrf
        @method('PUT')
        @include('clients._form', ['client' => $client, 'agents' => $agents])

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                Save Changes
            </button>
            <a href="{{ route('admin.clients.show', $client) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</x-agent-layout>