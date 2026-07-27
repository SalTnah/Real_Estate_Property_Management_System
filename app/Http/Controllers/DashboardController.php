<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        }

        $agent = $user->agent;

        if (! $agent) {
            return view('dashboard', [
                'stats' => ['active' => 0, 'sold' => 0, 'clients' => 0, 'viewingsToday' => 0],
                'recentProperties' => collect(),
                'todaySchedule' => collect(),
                'agent' => null,
            ]);
        }

        $stats = [
            'active' => $agent->properties()->where('status', 'Available')->count(),
            'sold' => $agent->properties()
                ->where('status', 'Sold')
                ->whereYear('updated_at', now()->year)
                ->count(),
            'clients' => $agent->clients()->count(),
            'viewingsToday' => $agent->appointments()
                ->where('appt_type', 'Viewing')
                ->whereDate('start_time', now()->toDateString())
                ->count(),
        ];

        $recentProperties = $agent->properties()
            ->with(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->latest()
            ->take(4)
            ->get();

        $todaySchedule = $agent->appointments()
            ->with(['property', 'client'])
            ->whereDate('start_time', now()->toDateString())
            ->orderBy('start_time')
            ->get();

        return view('dashboard', compact('stats', 'recentProperties', 'todaySchedule', 'agent'));
    }

    private function adminDashboard(Request $request)
    {
        $stats = [
            'active' => Property::where('status', 'Available')->count(),
            'sold' => Property::where('status', 'Sold')
                ->whereYear('updated_at', now()->year)
                ->count(),
            'clients' => Client::count(),
            'viewingsToday' => Appointment::where('appt_type', 'Viewing')
                ->whereDate('start_time', now()->toDateString())
                ->count(),
        ];

        $recentProperties = Property::with(['photos' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->latest()
            ->take(4)
            ->get();

        $allAgents = Agent::orderBy('f_name')->orderBy('l_name')->get();
        $selectedAgentId = $request->integer('agent_id') ?: null;

        $scheduleQuery = Appointment::with(['property', 'client', 'agent'])
            ->whereDate('start_time', now()->toDateString());

        if ($selectedAgentId) {
            $scheduleQuery->where('agent_id', $selectedAgentId);
        }

        $todaySchedule = $scheduleQuery->orderBy('start_time')->get();

        return view('dashboard', [
            'stats' => $stats,
            'recentProperties' => $recentProperties,
            'todaySchedule' => $todaySchedule,
            'agent' => null,
            'allAgents' => $allAgents,
            'selectedAgentId' => $selectedAgentId,
        ]);
    }
}