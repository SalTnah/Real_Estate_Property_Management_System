<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Appointment::class);

        $appointments = auth()->user()->agent->appointments()->with('client', 'property')->get();

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

        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'client_id' => 'nullable|exists:clients,id',
            'title' => 'required|string|max:255',
            'appt_type' => 'required|in:Viewing,Meeting,Call,Listing,Personal',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'nullable|in:Scheduled,Completed,Cancelled,No-show',
            'notes' => 'nullable|string',
        ]);

        $appointment = auth()->user()->agent->appointments()->create($validated);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment scheduled.');
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

        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'client_id' => 'nullable|exists:clients,id',
            'title' => 'required|string|max:255',
            'appt_type' => 'required|in:Viewing,Meeting,Call,Listing,Personal',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'nullable|in:Scheduled,Completed,Cancelled,No-show',
            'outcome' => 'nullable|in:Showed,No-show,Offer Made,Not Interested,Rescheduled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled.');
    }
}