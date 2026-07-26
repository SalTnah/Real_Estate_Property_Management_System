<x-agent-layout :title="'Schedule Appointment'">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Schedule Appointment</h1>
        <p class="mt-1 text-sm text-gray-500">Book a viewing, meeting, or call.</p>
    </div>

    <form method="POST" action="{{ route('agent.appointments.store') }}" class="mt-6 max-w-3xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        @csrf
        @include('appointments._form')

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                Save Appointment
            </button>
            <a href="{{ route('agent.appointments.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</x-agent-layout>