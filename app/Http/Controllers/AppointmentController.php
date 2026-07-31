<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\AgentAvailability;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Appointment::class);

        $user = auth()->user();
        $query = Appointment::with('client', 'property', 'agent');

        $isAdmin = ($user->is_admin ?? false) || ($user->role === 'admin');

        if (!$isAdmin && $user->agent) {
            $query->where('agent_id', $user->agent->id);
        }

        $appointments = $query->orderBy('start_time')->get();

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $this->authorize('create', Appointment::class);

        return view('appointments.create');
    }

public function store(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $user = auth()->user();
        $isAdmin = ($user->is_admin ?? false) || ($user->role === 'admin');

        $agentId = $user->agent->id ?? null;

        $rules = [
            'property_id' => 'nullable|exists:properties,id',
            'client_id' => 'nullable|exists:clients,id',
            'title' => 'required|string|max:255',
            'appt_type' => 'required|in:Viewing,Meeting,Call,Listing,Personal',
            'start_time' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request, $agentId) {
                    if (!$agentId) return;

                    $start = Carbon::parse($value);
                    $end = $request->input('end_time')
                        ? Carbon::parse($request->input('end_time'))
                        : $start;

                    $dayName = $start->format('l');

                    $withinAvailability = AgentAvailability::where('agent_id', $agentId)
                        ->where('days_of_week', $dayName)
                        ->get()
                        ->contains(function ($slot) use ($start, $end) {
                            $slotStart = Carbon::parse($start->format('Y-m-d') . ' ' . $slot->start_time->format('H:i'));
                            $slotEnd   = Carbon::parse($start->format('Y-m-d') . ' ' . $slot->end_time->format('H:i'));

                            return $start->gte($slotStart) && $end->lte($slotEnd);
                        });

                    if (!$withinAvailability) {
                        $fail('Time slot picked is outside the agent\'s available hours.');
                        return;
                    }

                    $conflictingAppointment = Appointment::where('agent_id', $agentId)
                        ->where('status', '!=', 'Cancelled')
                        ->get()
                        ->contains(function ($appt) use ($start, $end) {
                            return $start->lt($appt->end_time) && $end->gt($appt->start_time);
                        });

                    if ($conflictingAppointment) {
                        $fail('This time conflicts with another appointment already scheduled.');
                    }
                },
            ],
            'end_time' => 'required|date|after:start_time',
            'status' => 'nullable|in:Scheduled,Completed,Cancelled,No-show',
            'notes' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $validated['agent_id'] = $agentId;

        Appointment::create($validated);

        return redirect()->route('agent.appointments.index')->with('success', 'Appointment scheduled.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        return view('appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
        {
            $this->authorize('update', $appointment);

            $agentId = $appointment->agent_id;

            $validated = $request->validate([
                'property_id' => 'nullable|exists:properties,id',
                'client_id' => 'nullable|exists:clients,id',
                'title' => 'required|string|max:255',
                'appt_type' => 'required|in:Viewing,Meeting,Call,Listing,Personal',
                'start_time' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) use ($request, $agentId, $appointment) {
                        if (!$agentId) return;

                        $start = Carbon::parse($value);
                        $end = $request->input('end_time')
                            ? Carbon::parse($request->input('end_time'))
                            : $start;

                        $dayName = $start->format('l');

                        $withinAvailability = AgentAvailability::where('agent_id', $agentId)
                            ->where('days_of_week', $dayName)
                            ->get()
                            ->contains(function ($slot) use ($start, $end) {
                                $slotStart = Carbon::parse($start->format('Y-m-d') . ' ' . $slot->start_time->format('H:i'));
                                $slotEnd   = Carbon::parse($start->format('Y-m-d') . ' ' . $slot->end_time->format('H:i'));

                                return $start->gte($slotStart) && $end->lte($slotEnd);
                            });

                        if (!$withinAvailability) {
                            $fail('Time slot picked is outside the agent\'s available hours.');
                            return;
                        }

                        $conflictingAppointment = Appointment::where('agent_id', $agentId)
                            ->where('id', '!=', $appointment->id)
                            ->where('status', '!=', 'Cancelled')
                            ->get()
                            ->contains(function ($appt) use ($start, $end) {
                                return $start->lt($appt->end_time) && $end->gt($appt->start_time);
                            });

                        if ($conflictingAppointment) {
                            $fail('This time conflicts with another appointment already scheduled.');
                        }
                    },
                ],
                'end_time' => 'required|date|after:start_time',
                'status' => 'nullable|in:Scheduled,Completed,Cancelled,No-show',
                'outcome' => 'nullable|in:Showed,No-show,Offer Made,Not Interested,Rescheduled',
                'notes' => 'nullable|string',
            ]);

            $appointment->update($validated);

            return redirect()->route('agent.appointments.show', $appointment)->with('success', 'Appointment updated.');
        }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        $appointment->delete();

        return redirect()->route('agent.appointments.index')->with('success', 'Appointment cancelled.');
    }
}