@extends('layouts.agent')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- Page Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900" style="font-size: 24px; font-weight: 700;">Calendar</h1>
            <p class="text-sm text-gray-500 mt-1" style="color: #6b7280; font-size: 14px;">
                {{ $month->format('F Y') }} · {{ $viewingsThisWeek ?? 0 }} viewings, {{ $meetingsThisWeek ?? 0 }} meetings this week
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('agent.appointments.index') }}" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #374151; font-weight: 500; text-decoration: none;">
                List view
            </a>
            <a href="{{ route('agent.appointments.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                <x-agent.icon name="plus" class="h-4 w-4" />
                Schedule Appointment
            </a>
        </div>
    </div>

    {{-- Main Calendar Card --}}
    <div class="card card-pad" style="background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px;">
        
        {{-- Navigation & Tabs Header --}}
        <div class="flex items-center justify-between mb-6" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
            <div class="flex items-center gap-3" style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('agent.appointments.calendar', ['month' => $prevMonth]) }}" class="btn btn-outline btn-sm" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #374151; text-decoration: none;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                </a>
                <span class="bold" style="font-size: 16px; font-weight: 600; color: #111827;">{{ $month->format('F Y') }}</span>
                <a href="{{ route('agent.appointments.calendar', ['month' => $nextMonth]) }}" class="btn btn-outline btn-sm" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #374151; text-decoration: none;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="tabs" style="display: inline-flex; background: #f3f4f6; padding: 4px; border-radius: 8px; gap: 4px;">
                <span class="tab" style="padding: 6px 12px; font-size: 13px; font-weight: 500; color: #6b7280; cursor: not-allowed; opacity: 0.5;" title="Coming soon">Day</span>
                <span class="tab" style="padding: 6px 12px; font-size: 13px; font-weight: 500; color: #6b7280; cursor: not-allowed; opacity: 0.5;" title="Coming soon">Week</span>
                <span class="tab active" style="padding: 6px 12px; font-size: 13px; font-weight: 500; background: #fff; color: #111827; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">Month</span>
            </div>
        </div>

        {{-- Days of the Week Header --}}
        <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 8px; margin-bottom: 12px; text-align: center; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>

        {{-- Calendar Grid Cells (Looping through Controller's $weeks data) --}}
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @foreach ($weeks as $week)
                <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 8px;">
                    @foreach ($week as $day)
                        <div style="min-height: 110px; background: {{ $day['inMonth'] ? '#fff' : '#f9fafb' }}; border: 1px solid {{ $day['isToday'] ? '#2563eb' : '#e5e7eb' }}; border-radius: 8px; padding: 8px; display: flex; flex-direction: column; gap: 4px;">
                            <div style="font-size: 13px; font-weight: 500; color: {{ $day['inMonth'] ? '#374151' : '#9ca3af' }};">
                                {{ $day['date']->format('j') }}
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 3px; overflow-y: auto; max-height: 80px;">
                                @foreach ($day['appointments'] as $app)
                                    <a href="{{ route('agent.appointments.show', $app->id) }}" style="font-size: 11px; padding: 3px 6px; border-radius: 4px; background: #eff6ff; color: #1e40af; text-decoration: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $app->title ?? 'Appointment' }}">
                                        {{ $app->start_time->format('g:i A') }} {{ $app->title ?? 'Viewing' }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection