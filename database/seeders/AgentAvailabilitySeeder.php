<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\AgentAvailability;
use Illuminate\Database\Seeder;

class AgentAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $agent = Agent::first();

        $slots = [
            ['days_of_week' => 'Monday', 'start_time' => '09:00', 'end_time' => '12:00'],
            ['days_of_week' => 'Monday', 'start_time' => '13:00', 'end_time' => '17:00'],
            ['days_of_week' => 'Tuesday', 'start_time' => '09:00', 'end_time' => '17:00'],
            ['days_of_week' => 'Wednesday', 'start_time' => '10:00', 'end_time' => '14:00'],
            ['days_of_week' => 'Wednesday', 'start_time' => '15:00', 'end_time' => '18:00'],
            ['days_of_week' => 'Thursday', 'start_time' => '09:00', 'end_time' => '17:00'],
            ['days_of_week' => 'Friday', 'start_time' => '09:00', 'end_time' => '13:00'],
            ['days_of_week' => 'Friday', 'start_time' => '14:00', 'end_time' => '16:00'],
            ['days_of_week' => 'Saturday', 'start_time' => '10:00', 'end_time' => '14:00'],
            ['days_of_week' => 'Sunday', 'start_time' => '11:00', 'end_time' => '15:00'],
        ];

        foreach ($slots as $slot) {
            AgentAvailability::create(array_merge($slot, ['agent_id' => $agent->id]));
        }
    }
}