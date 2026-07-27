<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\ClientPreference;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Database\Seeder;

class DanaAlvarezContentSeeder extends Seeder
{
    public function run(): void
    {
        $dana = Agent::where('email', 'dana@estate.test')->first();

        if (! $dana) {
            $this->command->warn('Dana Alvarez agent not found, skipping DanaAlvarezContentSeeder.');

            return;
        }

        $clients = $this->seedClients($dana);
        $properties = $this->seedProperties($dana);
        $this->seedAppointments($dana, $clients, $properties);
        $this->seedNotifications($dana);
    }

    protected function seedClients(Agent $dana)
    {
        $clientData = [
            ['f_name' => 'Grace', 'l_name' => 'Kowalski', 'email' => 'grace.kowalski@example.com', 'phone' => '555-0201', 'location' => 'Austin, TX', 'type' => 'Buyer', 'lead_source' => 'Referral', 'lead_status' => 'Qualified', 'notes' => 'Pre-approved, wants to move fast.', 'client_since' => now()->subDays(8), 'preference' => ['budget_min' => 320000, 'budget_max' => 460000, 'pref_property_type' => 'House', 'pref_bedrooms' => 3, 'pref_bathrooms' => 2, 'pref_areas' => ['South Austin', 'Zilker'], 'must_haves' => ['Fenced yard', 'Updated HVAC'], 'additional_notes' => 'Has a dog, needs outdoor space.']],
            ['f_name' => 'Anthony', 'l_name' => 'Delgado', 'email' => 'anthony.delgado@example.com', 'phone' => '555-0202', 'location' => 'Austin, TX', 'type' => 'Seller', 'lead_source' => 'Website', 'lead_status' => 'Contacted', 'notes' => 'Relocating out of state, motivated seller.', 'client_since' => now()->subDays(4), 'preference' => null],
            ['f_name' => 'Naomi', 'l_name' => 'Chu', 'email' => 'naomi.chu@example.com', 'phone' => '555-0203', 'location' => 'Cedar Park, TX', 'type' => 'Buyer', 'lead_source' => 'Zillow', 'lead_status' => 'Nurturing', 'notes' => 'Still saving for a bigger down payment.', 'client_since' => now()->subDays(25), 'preference' => ['budget_min' => 275000, 'budget_max' => 380000, 'pref_property_type' => 'Condo', 'pref_bedrooms' => 2, 'pref_bathrooms' => 2, 'pref_areas' => ['Cedar Park'], 'must_haves' => ['Elevator', 'Assigned parking'], 'additional_notes' => 'Works downtown, wants an easy commute.']],
            ['f_name' => 'Felix', 'l_name' => 'Novak', 'email' => 'felix.novak@example.com', 'phone' => '555-0204', 'location' => 'Austin, TX', 'type' => 'Both', 'lead_source' => 'Open House', 'lead_status' => 'Client', 'notes' => 'Selling starter home to buy something bigger.', 'client_since' => now()->subDays(90), 'preference' => ['budget_min' => 500000, 'budget_max' => 650000, 'pref_property_type' => 'House', 'pref_bedrooms' => 4, 'pref_bathrooms' => 3, 'pref_areas' => ['Barton Hills', 'Travis Heights'], 'must_haves' => ['Home office', 'Two-car garage'], 'additional_notes' => 'Needs to close on the sale before buying.']],
            ['f_name' => 'Rosalind', 'l_name' => 'Ahmadi', 'email' => 'rosalind.ahmadi@example.com', 'phone' => '555-0205', 'location' => 'Round Rock, TX', 'type' => 'Renter', 'lead_source' => 'Instagram', 'lead_status' => 'New', 'notes' => 'Short-term rental while house hunting.', 'client_since' => now()->subDays(1), 'preference' => null],
        ];

        return collect($clientData)->map(function ($data) use ($dana) {
            $preference = $data['preference'] ?? null;
            unset($data['preference']);

            $client = Client::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['agent_id' => $dana->id])
            );

            if ($preference && ! $client->preference()->exists()) {
                ClientPreference::create(array_merge($preference, ['client_id' => $client->id]));
            }

            return $client;
        });
    }

    protected function seedProperties(Agent $dana)
    {
        $propertyData = [
            ['title' => 'Zilker Bungalow', 'property_type' => 'House', 'status' => 'Available', 'street_address' => '1108 Bluebonnet Ln', 'city' => 'Austin', 'state' => 'TX', 'zip' => '78704', 'price' => 549000, 'size_sqft' => 1850, 'bedrooms' => 3, 'bathrooms' => 2, 'year_built' => 1998],
            ['title' => 'Barton Hills Modern', 'property_type' => 'House', 'status' => 'Available', 'street_address' => '2244 Rabb Rd', 'city' => 'Austin', 'state' => 'TX', 'zip' => '78704', 'price' => 675000, 'size_sqft' => 2400, 'bedrooms' => 4, 'bathrooms' => 3, 'year_built' => 2015],
            ['title' => 'Cedar Park Courtyard Condo', 'property_type' => 'Condo', 'status' => 'Pending', 'street_address' => '900 Whitestone Blvd', 'city' => 'Cedar Park', 'state' => 'TX', 'zip' => '78613', 'price' => 335000, 'size_sqft' => 1120, 'bedrooms' => 2, 'bathrooms' => 2, 'year_built' => 2009],
            ['title' => 'Travis Heights Craftsman', 'property_type' => 'House', 'status' => 'Available', 'street_address' => '1802 Newning Ave', 'city' => 'Austin', 'state' => 'TX', 'zip' => '78704', 'price' => 620000, 'size_sqft' => 2100, 'bedrooms' => 4, 'bathrooms' => 3, 'year_built' => 1962],
            ['title' => 'Downtown Loft', 'property_type' => 'Condo', 'status' => 'Sold', 'street_address' => '210 Lavaca St', 'city' => 'Austin', 'state' => 'TX', 'zip' => '78701', 'price' => 410000, 'size_sqft' => 980, 'bedrooms' => 1, 'bathrooms' => 1, 'year_built' => 2012],
        ];

        return collect($propertyData)->map(function ($data) use ($dana) {
            $property = Property::firstOrCreate(
                ['agent_id' => $dana->id, 'street_address' => $data['street_address']],
                array_merge($data, ['agent_id' => $dana->id, 'last_activity_at' => now()->subDays(rand(0, 10))])
            );

            if ($property->wasRecentlyCreated) {
                PropertyPhoto::create([
                    'property_id' => $property->id,
                    'photo_url' => "https://picsum.photos/seed/dana-property{$property->id}-1/800/600",
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);

                PropertyPhoto::create([
                    'property_id' => $property->id,
                    'photo_url' => "https://picsum.photos/seed/dana-property{$property->id}-2/800/600",
                    'is_primary' => false,
                    'sort_order' => 1,
                ]);
            }

            return $property;
        });
    }

    protected function seedAppointments(Agent $dana, $clients, $properties)
    {
        $templates = [
            ['title' => 'Buyer strategy session', 'appt_type' => 'Meeting', 'start_time' => now()->subDays(6)->setTime(9, 0), 'end_time' => now()->subDays(6)->setTime(10, 0), 'status' => 'Completed', 'outcome' => 'Showed', 'notes' => 'Walked through pre-approval letter and target areas.'],
            ['title' => 'Showing - Zilker Bungalow', 'appt_type' => 'Viewing', 'start_time' => now()->subDays(4)->setTime(15, 0), 'end_time' => now()->subDays(4)->setTime(16, 0), 'status' => 'Completed', 'outcome' => 'Offer Made', 'notes' => 'Loved the backyard, submitting an offer.'],
            ['title' => 'Listing walkthrough', 'appt_type' => 'Listing', 'start_time' => now()->subDays(2)->setTime(11, 0), 'end_time' => now()->subDays(2)->setTime(12, 0), 'status' => 'Completed', 'outcome' => 'Showed', 'notes' => 'Discussed staging and pricing strategy.'],
            ['title' => 'Showing - Travis Heights Craftsman', 'appt_type' => 'Viewing', 'start_time' => now()->addDay()->setTime(13, 0), 'end_time' => now()->addDay()->setTime(14, 0), 'status' => 'Scheduled', 'outcome' => null, 'notes' => 'Second look before making a decision.'],
            ['title' => 'Contract signing', 'appt_type' => 'Meeting', 'start_time' => now()->addDays(2)->setTime(10, 0), 'end_time' => now()->addDays(2)->setTime(10, 30), 'status' => 'Scheduled', 'outcome' => null, 'notes' => null],
            ['title' => 'Check-in call', 'appt_type' => 'Call', 'start_time' => now()->addDays(3)->setTime(9, 30), 'end_time' => now()->addDays(3)->setTime(9, 45), 'status' => 'Scheduled', 'outcome' => null, 'notes' => null],
        ];

        if ($clients->isEmpty() && $properties->isEmpty()) {
            return;
        }

        foreach ($templates as $i => $data) {
            $client = $data['appt_type'] === 'Personal' || $clients->isEmpty()
                ? null
                : $clients->get($i % $clients->count());

            $property = in_array($data['appt_type'], ['Viewing', 'Listing'], true) && $properties->isNotEmpty()
                ? $properties->get($i % $properties->count())
                : null;

            Appointment::create(array_merge($data, [
                'agent_id' => $dana->id,
                'client_id' => $client?->id,
                'property_id' => $property?->id,
            ]));
        }
    }

    protected function seedNotifications(Agent $dana)
    {
        $notifications = [
            ['type' => 'client', 'title' => 'New lead assigned', 'body' => 'Rosalind Ahmadi was added to your book of business.', 'read_at' => null],
            ['type' => 'appointment', 'title' => 'Offer submitted', 'body' => 'An offer was submitted on the Zilker Bungalow listing.', 'read_at' => null],
            ['type' => 'system', 'title' => 'Listing update ready', 'body' => 'Your Travis Heights Craftsman listing photos are ready for review.', 'read_at' => now()->subHours(6)],
        ];

        foreach ($notifications as $data) {
            Notification::create(array_merge($data, ['agent_id' => $dana->id]));
        }
    }
}