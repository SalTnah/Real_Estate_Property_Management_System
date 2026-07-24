<?php

namespace App\Http\Controllers;

use App\Models\AgentAvailability;
use Illuminate\Http\Request;

class AgentAvailabilityController extends Controller
{
    public function index()
    {
        $availability = auth()->user()->agent->availability;
        return view('availability.index', compact('availability'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'days_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        auth()->user()->agent->availability()->create($validated);

        return redirect()->route('availability.index')->with('success', 'Availability added.');
    }

    public function update(Request $request, AgentAvailability $agentAvailability)
    {
        abort_unless($agentAvailability->agent_id === auth()->user()->agent->id, 403);

        $validated = $request->validate([
            'days_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $agentAvailability->update($validated);
        return redirect()->route('availability.index')->with('success', 'Availability updated.');
    }

    public function destroy(AgentAvailability $agentAvailability)
    {
        abort_unless($agentAvailability->agent_id === auth()->user()->agent->id, 403);

        $agentAvailability->delete();

        return redirect()->route('availability.index')->with('success', 'Availability removed.');
    }
}