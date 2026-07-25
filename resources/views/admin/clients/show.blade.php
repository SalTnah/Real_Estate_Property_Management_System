<x-agent-layout :title="$client->f_name.' '.$client->l_name">
    <nav class="text-sm text-gray-500">
        <a href="{{ route('admin.clients.index') }}" class="text-blue-600 hover:text-blue-700">Clients</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700">{{ $client->f_name }} {{ $client->l_name }}</span>
    </nav>

    <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">
        @include('clients._detail_main', ['client' => $client])

        <div class="space-y-6">
            @if ($client->agent)
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Listing Agent</h2>
                    <p class="mt-2 font-medium text-gray-900">{{ $client->agent->f_name }} {{ $client->agent->l_name }}</p>
                    <p class="text-sm text-gray-500">{{ $client->agent->agency_name }}</p>
                    @if ($client->agent->phone_num)
                        <p class="mt-1 text-sm text-gray-500">{{ $client->agent->phone_num }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-agent-layout>
