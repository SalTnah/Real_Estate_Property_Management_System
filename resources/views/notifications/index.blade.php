@php
    $typeColors = [
        'appointment' => 'blue',
        'client' => 'purple',
        'property' => 'green',
        'system' => 'gray',
    ];
@endphp

<x-agent-layout :title="'Notifications'">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="mt-1 text-sm text-gray-500">Updates about your appointments, clients, and account.</p>
        </div>

        <div class="flex shrink-0 items-center gap-3">
            @if ($notifications->whereNotNull('read_at')->isNotEmpty())
                <form method="POST" action="{{ route('notifications.clear-read') }}" onsubmit="return confirm('Clear all read notifications? Unread ones will stay.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Clear read
                    </button>
                </form>
            @endif

            @if ($notifications->whereNull('read_at')->isNotEmpty())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
        @if ($notifications->isEmpty())
            <p class="px-5 py-8 text-center text-sm text-gray-500">You're all caught up — no notifications yet.</p>
        @else
            <div class="divide-y divide-gray-100">
                @foreach ($notifications as $notification)
                    <div class="flex items-start justify-between gap-4 px-5 py-4 {{ is_null($notification->read_at) ? 'bg-blue-50/40' : '' }}">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ is_null($notification->read_at) ? 'bg-blue-600' : 'bg-transparent' }}"></span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                    <x-agent.pill :color="$typeColors[$notification->type] ?? 'gray'">
                                        {{ ucfirst($notification->type) }}
                                    </x-agent.pill>
                                </div>
                                @if ($notification->body)
                                    <p class="mt-1 text-sm text-gray-500">{{ $notification->body }}</p>
                                @endif
                                <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-3">
                            @if (is_null($notification->read_at))
                                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm font-medium text-blue-600 hover:text-blue-700">Mark read</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('notifications.destroy', $notification) }}" onsubmit="return confirm('Remove this notification?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-agent-layout>