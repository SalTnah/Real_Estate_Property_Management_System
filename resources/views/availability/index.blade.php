@php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
@endphp

<x-agent-layout :title="'Availability'">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Availability</h1>
        <p class="mt-1 text-sm text-gray-500">Set the days and hours you're open for viewings and meetings.</p>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 lg:col-span-2">
            <div class="px-5 py-4">
                <h2 class="text-base font-semibold text-gray-900">Your Schedule</h2>
            </div>

            @if ($availability->isEmpty())
                <p class="px-5 pb-6 text-sm text-gray-500">No availability set yet — add your first time block.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($availability->sortBy(fn ($a) => array_search($a->days_of_week, $days)) as $slot)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $slot->days_of_week }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Illuminate\Support\Carbon::parse($slot->start_time)->format('g:i A') }}
                                    &ndash;
                                    {{ \Illuminate\Support\Carbon::parse($slot->end_time)->format('g:i A') }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('availability.destroy', $slot) }}" onsubmit="return confirm('Remove this time block?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-base font-semibold text-gray-900">Add a Time Block</h2>
            <form method="POST" action="{{ route('availability.store') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <x-input-label for="days_of_week" value="Day" />
                    <select id="days_of_week" name="days_of_week" class="block w-full rounded-lg border-gray-300 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach ($days as $day)
                            <option value="{{ $day }}" @selected(old('days_of_week') === $day)>{{ $day }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('days_of_week')" />
                </div>

                <div>
                    <x-input-label for="start_time" value="Start time" />
                    <x-text-input id="start_time" name="start_time" type="time" required :value="old('start_time')" />
                    <x-input-error :messages="$errors->get('start_time')" />
                </div>

                <div>
                    <x-input-label for="end_time" value="End time" />
                    <x-text-input id="end_time" name="end_time" type="time" required :value="old('end_time')" />
                    <x-input-error :messages="$errors->get('end_time')" />
                </div>

                <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Add Time Block
                </button>
            </form>
        </div>
    </div>
</x-agent-layout>
