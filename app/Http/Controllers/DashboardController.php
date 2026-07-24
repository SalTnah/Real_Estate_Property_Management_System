<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::user()->agent;

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
}
