<x-agent-layout :title="'Calendar'">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $currentMonth->format('F Y') }}</h1>
            <p class="mt-1 text-sm text-gray-500">Your scheduled appointments this month.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('calendar.index', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
               class="inline-flex items-center rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-200 transition hover:bg-gray-50">
                &larr; Prev
            </a>
            <a href="{{ route('calendar.index') }}"
               class="inline-flex items-center rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-200 transition hover:bg-gray-50">
                Today
            </a>
            <a href="{{ route('calendar.index', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
               class="inline-flex items-center rounded-lg bg-white px-3 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-200 transition hover:bg-gray-50">
                Next &rarr;
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
        <div class="grid grid-cols-7 gap-px bg-gray-100 text-xs font-medium uppercase tracking-wide text-gray-400">
            @foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $label)
                <div class="bg-white px-3 py-2">{{ $label }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-px bg-gray-100">
            @foreach ($days as $day)
                @php
                    $key = $day->format('Y-m-d');
                    $dayAppointments = $appointments->get($key, collect());
                    $isCurrentMonth = $day->month === $currentMonth->month;
                    $isToday = $day->isToday();
                @endphp
                <div class="min-h-[110px] bg-white p-2 {{ $isCurrentMonth ? '' : 'opacity-40' }}">
                    <span class="text-xs {{ $isToday ? 'flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 font-semibold text-white' : 'text-gray-500' }}">
                        {{ $day->day }}
                    </span>

                    <div class="mt-1 space-y-1">
                        @foreach ($dayAppointments->take(3) as $appt)
                            <a href="{{ route('appointments.show', $appt) }}"
                               class="block truncate rounded bg-blue-50 px-1.5 py-0.5 text-[11px] font-medium text-blue-700 hover:bg-blue-100">
                                {{ $appt->start_time->format('g:ia') }} {{ $appt->title }}
                            </a>
                        @endforeach
                        @if ($dayAppointments->count() > 3)
                            <span class="block px-1.5 text-[11px] text-gray-400">+{{ $dayAppointments->count() - 3 }} more</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-agent-layout>