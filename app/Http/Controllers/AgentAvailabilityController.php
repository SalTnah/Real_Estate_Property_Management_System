<?php

namespace App\Http\Controllers;

use App\Models\AgentAvailability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgentAvailabilityController extends Controller
{
    public function index()
    {
        $availability = auth()->user()->agent->availability;
        return view('availability.index', compact('availability'));
    }

    public function store(Request $request)
    {
        $agentId = auth()->user()->agent->id;

        $validated = $request->validate([
            'days_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
                function ($attribute, $value, $fail) use ($request, $agentId) {
                    $this->checkOverlap($request, $agentId, $value, $fail);
                },
            ],
        ]);

        auth()->user()->agent->availability()->create($validated);

        return redirect()->route('availability.index')->with('success', 'Availability added.');
    }

    public function update(Request $request, AgentAvailability $agentAvailability)
    {
        abort_unless($agentAvailability->agent_id === auth()->user()->agent->id, 403);

        $agentId = auth()->user()->agent->id;

        $validated = $request->validate([
            'days_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
                function ($attribute, $value, $fail) use ($request, $agentId, $agentAvailability) {
                    $this->checkOverlap($request, $agentId, $value, $fail, $agentAvailability->id);
                },
            ],
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

    /**
     * Check whether the submitted time range overlaps an existing availability
     * slot for this agent on the same day. Excludes $excludeId when editing.
     */
    private function checkOverlap(Request $request, int $agentId, string $endValue, \Closure $fail, ?int $excludeId = null): void
    {
        $day = $request->input('days_of_week');
        $newStart = Carbon::parse($request->input('start_time'));
        $newEnd = Carbon::parse($endValue);

        $query = AgentAvailability::where('agent_id', $agentId)
            ->where('days_of_week', $day);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $overlaps = $query->get()->contains(function ($slot) use ($newStart, $newEnd) {
            $existingStart = Carbon::parse($slot->start_time->format('H:i'));
            $existingEnd = Carbon::parse($slot->end_time->format('H:i'));

            return $newStart->lt($existingEnd) && $newEnd->gt($existingStart);
        });

        if ($overlaps) {
            $fail("This time slot overlaps with an existing availability window on {$day}.");
        }
    }
}