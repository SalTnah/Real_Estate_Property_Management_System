<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        Agent::all()->each(function (Agent $agent) {
            Notification::create([
                'agent_id' => $agent->id,
                'type' => 'appointment',
                'title' => 'Upcoming appointment tomorrow',
                'body' => 'You have a property viewing scheduled for tomorrow morning.',
                'link' => null,
                'read_at' => null,
            ]);

            Notification::create([
                'agent_id' => $agent->id,
                'type' => 'client',
                'title' => 'New client inquiry',
                'body' => 'A new lead submitted an inquiry through one of your listings.',
                'link' => null,
                'read_at' => null,
            ]);

            Notification::create([
                'agent_id' => $agent->id,
                'type' => 'system',
                'title' => 'Weekly summary is ready',
                'body' => 'Your activity summary for last week is now available.',
                'link' => null,
                'read_at' => now()->subDay(),
            ]);
        });
    }
}
