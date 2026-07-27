<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Property;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['title' => 'Initial buyer consultation', 'appt_type' => 'Meeting', 'start_time' => now()->subDays(7)->setTime(10, 0), 'end_time' => now()->subDays(7)->setTime(11, 0), 'status' => 'Completed', 'outcome' => 'Showed', 'notes' => 'Discussed budget and must-haves.'],
            ['title' => 'Property viewing - downtown condo', 'appt_type' => 'Viewing', 'start_time' => now()->subDays(5)->setTime(14, 0), 'end_time' => now()->subDays(5)->setTime(15, 0), 'status' => 'Completed', 'outcome' => 'Not Interested', 'notes' => 'Client felt it was too small.'],
            ['title' => 'Follow-up phone call', 'appt_type' => 'Call', 'start_time' => now()->subDays(3)->setTime(9, 30), 'end_time' => now()->subDays(3)->setTime(9, 45), 'status' => 'Completed', 'outcome' => 'Showed', 'notes' => 'Reviewed new listings.'],
            ['title' => 'Listing consultation', 'appt_type' => 'Listing', 'start_time' => now()->subDays(1)->setTime(13, 0), 'end_time' => now()->subDays(1)->setTime(14, 0), 'status' => 'Completed', 'outcome' => 'Offer Made', 'notes' => 'Prepared CMA for seller.'],
            ['title' => 'Property viewing - suburban house', 'appt_type' => 'Viewing', 'start_time' => now()->addDay()->setTime(11, 0), 'end_time' => now()->addDay()->setTime(12, 0), 'status' => 'Scheduled', 'outcome' => null, 'notes' => 'Bringing spouse along this time.'],
            ['title' => 'Second showing - townhouse', 'appt_type' => 'Viewing', 'start_time' => now()->addDays(2)->setTime(16, 0), 'end_time' => now()->addDays(2)->setTime(17, 0), 'status' => 'Scheduled', 'outcome' => null, 'notes' => null],
            ['title' => 'Contract review meeting', 'appt_type' => 'Meeting', 'start_time' => now()->addDays(3)->setTime(10, 0), 'end_time' => now()->addDays(3)->setTime(11, 30), 'status' => 'Scheduled', 'outcome' => null, 'notes' => 'Bring updated disclosure forms.'],
            ['title' => 'Quick check-in call', 'appt_type' => 'Call', 'start_time' => now()->addDays(4)->setTime(9, 0), 'end_time' => now()->addDays(4)->setTime(9, 15), 'status' => 'Scheduled', 'outcome' => null, 'notes' => null],
            ['title' => 'Personal errand', 'appt_type' => 'Personal', 'start_time' => now()->addDays(5)->setTime(12, 0), 'end_time' => now()->addDays(5)->setTime(13, 0), 'status' => 'Scheduled', 'outcome' => null, 'notes' => null],
            ['title' => 'Missed viewing appointment', 'appt_type' => 'Viewing', 'start_time' => now()->subDays(10)->setTime(15, 0), 'end_time' => now()->subDays(10)->setTime(16, 0), 'status' => 'No-show', 'outcome' => 'No-show', 'notes' => 'Client never confirmed.'],
        ];

        // Build appointments per-agent so client_id/property_id always
        // belong to the same agent as the appointment itself.
        Agent::all()->each(function (Agent $agent) use ($templates) {
            $clients = Client::where('agent_id', $agent->id)->get();
            $properties = Property::where('agent_id', $agent->id)->get();

            if ($clients->isEmpty() && $properties->isEmpty()) {
                return;
            }

            foreach ($templates as $i => $data) {
                // 'Personal' appointments have no client/property on purpose.
                $client = $data['appt_type'] === 'Personal' || $clients->isEmpty()
                    ? null
                    : $clients->get($i % $clients->count());

                $property = in_array($data['appt_type'], ['Viewing', 'Listing'], true) && $properties->isNotEmpty()
                    ? $properties->get($i % $properties->count())
                    : null;

                Appointment::create(array_merge($data, [
                    'agent_id' => $agent->id,
                    'client_id' => $client?->id,
                    'property_id' => $property?->id,
                ]));
            }
        });
    }
}