<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientPreference;
use Illuminate\Database\Seeder;

class ClientPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();

        $preferences = [
            ['budget_min' => 250000, 'budget_max' => 400000, 'pref_property_type' => 'Single Family', 'pref_bedrooms' => 3, 'pref_bathrooms' => 2, 'pref_areas' => ['Downtown', 'East Austin'], 'must_haves' => ['Garage', 'Fenced yard'], 'additional_notes' => 'Wants move-in ready.'],
            ['budget_min' => 500000, 'budget_max' => 700000, 'pref_property_type' => 'Single Family', 'pref_bedrooms' => 4, 'pref_bathrooms' => 3, 'pref_areas' => ['Round Rock'], 'must_haves' => ['Home office', 'Pool'], 'additional_notes' => null],
            ['budget_min' => 150000, 'budget_max' => 220000, 'pref_property_type' => 'Condo', 'pref_bedrooms' => 1, 'pref_bathrooms' => 1, 'pref_areas' => ['Downtown'], 'must_haves' => ['Gym', 'Parking'], 'additional_notes' => 'First-time buyer.'],
            ['budget_min' => 180000, 'budget_max' => 260000, 'pref_property_type' => 'Townhouse', 'pref_bedrooms' => 2, 'pref_bathrooms' => 2, 'pref_areas' => ['Cedar Park', 'Pflugerville'], 'must_haves' => ['Pet-friendly'], 'additional_notes' => 'Renting for now.'],
            ['budget_min' => 300000, 'budget_max' => 450000, 'pref_property_type' => 'Single Family', 'pref_bedrooms' => 3, 'pref_bathrooms' => 2, 'pref_areas' => ['Georgetown'], 'must_haves' => ['Large backyard'], 'additional_notes' => null],
            ['budget_min' => 600000, 'budget_max' => 850000, 'pref_property_type' => 'Multi-family', 'pref_bedrooms' => 6, 'pref_bathrooms' => 4, 'pref_areas' => ['South Austin'], 'must_haves' => ['Separate units'], 'additional_notes' => 'Looking for rental income.'],
            ['budget_min' => 200000, 'budget_max' => 300000, 'pref_property_type' => 'Condo', 'pref_bedrooms' => 2, 'pref_bathrooms' => 1, 'pref_areas' => ['Downtown', 'North Loop'], 'must_haves' => ['Balcony'], 'additional_notes' => null],
            ['budget_min' => 350000, 'budget_max' => 500000, 'pref_property_type' => 'Single Family', 'pref_bedrooms' => 3, 'pref_bathrooms' => 2, 'pref_areas' => ['Austin'], 'must_haves' => ['Good schools nearby'], 'additional_notes' => 'Has two kids.'],
            ['budget_min' => 120000, 'budget_max' => 180000, 'pref_property_type' => 'Townhouse', 'pref_bedrooms' => 1, 'pref_bathrooms' => 1, 'pref_areas' => ['Pflugerville'], 'must_haves' => ['Washer/dryer hookup'], 'additional_notes' => 'Relocating for work.'],
            ['budget_min' => 400000, 'budget_max' => 550000, 'pref_property_type' => 'Single Family', 'pref_bedrooms' => 4, 'pref_bathrooms' => 3, 'pref_areas' => ['Austin'], 'must_haves' => ['Modern kitchen'], 'additional_notes' => 'Downsizing but wants quality finishes.'],
        ];

        foreach ($clients as $i => $client) {
            if (! isset($preferences[$i])) {
                break;
            }

            ClientPreference::create(array_merge($preferences[$i], ['client_id' => $client->id]));
        }
    }
}
