@php
    $statusColors = [
        'Scheduled' => 'blue', 'Completed' => 'green', 'Cancelled' => 'red', 'No-show' => 'amber',
    ];
@endphp

<x-agent-layout :title="$appointment->title">
    <nav class="text-sm text-gray-500">
        <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-700">Appointments</a>
        <span class="mx-1">/</span>
        <span class="text-gray-700">{{ $appointment->title }}</span>
    </nav>

    <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $appointment->title }}</h1>
                        <p class="mt-1 text-sm text-gray-500">{{ $appointment->appt_type }}</p>
                    </div>
                    <x-agent.pill :color="$statusColors[$appointment->status] ?? 'gray'">
                        {{ $appointment->status }}
                    </x-agent.pill>
                </div>

                <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-gray-100 pt-5 text-sm sm:grid-cols-3">
                    <div>
                        <dt class="text-gray-400">Start</dt>
                        <dd class="mt-0.5 font-medium text-gray-900">{{ $appointment->start_time->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">End</dt>
                        <dd class="mt-0.5 font-medium text-gray-900">{{ $appointment->end_time->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Outcome</dt>
                        <dd class="mt-0.5 font-medium text-gray-900">{{ $appointment->outcome ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Client</dt>
                        <dd class="mt-0.5 font-medium text-gray-900">
                            @if ($appointment->client)
                                <a href="{{ route('clients.show', $appointment->client) }}" class="text-blue-600 hover:text-blue-700">
                                    {{ $appointment->client->f_name }} {{ $appointment->client->l_name }}
                                </a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Property</dt>
                        <dd class="mt-0.5 font-medium text-gray-900">
                            @if ($appointment->property)
                                <a href="{{ route('properties.show', $appointment->property) }}" class="text-blue-600 hover:text-blue-700">
                                    {{ $appointment->property->title }}
                                </a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                </dl>

                @if ($appointment->notes)
                    <div class="mt-5 border-t border-gray-100 pt-5">
                        <dt class="text-sm text-gray-400">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $appointment->notes }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            @can('update', $appointment)
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <a href="{{ route('appointments.edit', $appointment) }}" class="block w-full rounded-lg border border-gray-200 px-4 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Edit Appointment
                    </a>
                    @can('delete', $appointment)
                        <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="mt-2" onsubmit="return confirm('Cancel this appointment? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full rounded-lg border border-red-200 px-4 py-2.5 text-center text-sm font-semibold text-red-600 hover:bg-red-50">
                                Cancel Appointment
                            </button>
                        </form>
                    @endcan
                </div>
            @endcan
        </div>
    </div>
</x-agent-layout>
