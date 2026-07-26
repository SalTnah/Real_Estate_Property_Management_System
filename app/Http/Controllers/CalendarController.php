<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);

        $monthParam = $request->string('month')->toString();
        $month = preg_match('/^\d{4}-\d{2}$/', $monthParam)
            ? Carbon::createFromFormat('Y-m-d', $monthParam.'-01')->startOfMonth()
            : Carbon::now()->startOfMonth();

        $gridStart = $month->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $gridEnd = $month->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $user = auth()->user();

        $query = Appointment::with('client', 'property', 'agent')
            ->whereBetween('start_time', [$gridStart, $gridEnd]);

        $isAdmin = ($user->is_admin ?? false) || ($user->role === 'admin');

        if (!$isAdmin && $user->agent) {
            $query->where('agent_id', $user->agent->id);
        }

        $appointments = $query->orderBy('start_time')->get();

        $byDay = $appointments->groupBy(fn ($appt) => $appt->start_time->format('Y-m-d'));

        $weeks = [];
        $cursor = $gridStart->copy();
        while ($cursor->lte($gridEnd)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $key = $cursor->format('Y-m-d');
                $week[] = [
                    'date' => $cursor->copy(),
                    'inMonth' => $cursor->month === $month->month,
                    'isToday' => $cursor->isToday(),
                    'appointments' => $byDay->get($key, collect()),
                ];
                $cursor->addDay();
            }
            $weeks[] = $week;
        }

        $thisWeekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $thisWeekEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);
        $thisWeek = $appointments->filter(
            fn ($appt) => $appt->start_time->between($thisWeekStart, $thisWeekEnd)
        );

        return view('calendar.index', [
            'month' => $month,
            'weeks' => $weeks,
            'viewingsThisWeek' => $thisWeek->where('appt_type', 'Viewing')->count(),
            'meetingsThisWeek' => $thisWeek->where('appt_type', 'Meeting')->count(),
            'prevMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
        ]);
    }
}