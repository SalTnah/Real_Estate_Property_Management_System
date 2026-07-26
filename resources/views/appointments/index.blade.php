@php
    $statusColors = [
        'Scheduled' => 'blue',
        'Completed' => 'green',
        'Cancelled' => 'red',
        'No-show' => 'amber',
    ];
@endphp

<x-agent-layout :title="'Appointments'">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
            <p class="mt-1 text-sm text-gray-500">Your upcoming and past viewings, meetings, and calls.</p>
        </div>

        @can('create', \App\Models\Appointment::class)
            <a href="{{ route('appointments.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                Schedule Appointment
            </a>
        @endcan
    </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
        @if ($appointments->isEmpty())
            <p class="px-5 py-8 text-center text-sm text-gray-500">No appointments scheduled yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                            <th class="px-5 py-3 font-medium">Title</th>
                            <th class="px-5 py-3 font-medium">Type</th>
                            <th class="px-5 py-3 font-medium">Client</th>
                            <th class="px-5 py-3 font-medium">Property</th>
                            <th class="px-5 py-3 font-medium">Start</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments->sortBy('start_time') as $appointment)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('appointments.show', $appointment) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                        {{ $appointment->title }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-gray-700">{{ $appointment->appt_type }}</td>
                                <td class="px-5 py-3 text-gray-700">
                                    {{ $appointment->client ? $appointment->client->f_name.' '.$appointment->client->l_name : '—' }}
                                </td>
                                <td class="px-5 py-3 text-gray-700">{{ $appointment->property->title ?? '—' }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $appointment->start_time->format('M j, Y g:i A') }}</td>
                                <td class="px-5 py-3">
                                    <x-agent.pill :color="$statusColors[$appointment->status] ?? 'gray'">
                                        {{ $appointment->status }}
                                    </x-agent.pill>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-agent-layout>
