<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $agent = Agent::first();

        $clients = [
            ['f_name' => 'Marcus', 'l_name' => 'Reed', 'email' => 'marcus.reed@example.com', 'phone' => '555-0101', 'location' => 'Austin, TX', 'type' => 'Buyer', 'lead_source' => 'Website', 'lead_status' => 'New', 'notes' => 'Looking for a 3-bed near downtown.', 'client_since' => now()->subDays(2)],
            ['f_name' => 'Priya', 'l_name' => 'Nair', 'email' => 'priya.nair@example.com', 'phone' => '555-0102', 'location' => 'Austin, TX', 'type' => 'Seller', 'lead_source' => 'Referral', 'lead_status' => 'Contacted', 'notes' => 'Selling family home, flexible on timeline.', 'client_since' => now()->subDays(5)],
            ['f_name' => 'Owen', 'l_name' => 'Fitzgerald', 'email' => 'owen.fitz@example.com', 'phone' => '555-0103', 'location' => 'Round Rock, TX', 'type' => 'Both', 'lead_source' => 'Zillow', 'lead_status' => 'Qualified', 'notes' => 'Upsizing from a condo to a house.', 'client_since' => now()->subDays(10)],
            ['f_name' => 'Simone', 'l_name' => 'Blackwood', 'email' => 'simone.b@example.com', 'phone' => '555-0104', 'location' => 'Austin, TX', 'type' => 'Renter', 'lead_source' => 'Walk-in', 'lead_status' => 'Nurturing', 'notes' => 'Needs a pet-friendly rental by next month.', 'client_since' => now()->subDays(14)],
            ['f_name' => 'Devon', 'l_name' => 'Whitfield', 'email' => 'devon.w@example.com', 'phone' => '555-0105', 'location' => 'Cedar Park, TX', 'type' => 'Buyer', 'lead_source' => 'Facebook Ads', 'lead_status' => 'Client', 'notes' => 'Closed on a townhouse last quarter.', 'client_since' => now()->subDays(45)],
            ['f_name' => 'Lena', 'l_name' => 'Marchetti', 'email' => 'lena.marchetti@example.com', 'phone' => '555-0106', 'location' => 'Austin, TX', 'type' => 'Seller', 'lead_source' => 'Referral', 'lead_status' => 'Closed', 'notes' => 'Sold within 3 weeks of listing.', 'client_since' => now()->subDays(60)],
            ['f_name' => 'Tariq', 'l_name' => 'Osei', 'email' => 'tariq.osei@example.com', 'phone' => '555-0107', 'location' => 'Pflugerville, TX', 'type' => 'Buyer', 'lead_source' => 'Website', 'lead_status' => 'Lost', 'notes' => 'Went with another agency.', 'client_since' => now()->subDays(30)],
            ['f_name' => 'Harriet', 'l_name' => 'Voss', 'email' => 'harriet.voss@example.com', 'phone' => '555-0108', 'location' => 'Austin, TX', 'type' => 'Both', 'lead_source' => 'Open House', 'lead_status' => 'New', 'notes' => 'Met at Saturday open house, very interested.', 'client_since' => now()->subDay()],
            ['f_name' => 'Callum', 'l_name' => 'Pruitt', 'email' => 'callum.pruitt@example.com', 'phone' => '555-0109', 'location' => 'Georgetown, TX', 'type' => 'Renter', 'lead_source' => 'Instagram', 'lead_status' => 'Contacted', 'notes' => 'Relocating for work in 2 months.', 'client_since' => now()->subDays(3)],
            ['f_name' => 'Yuki', 'l_name' => 'Tanaka', 'email' => 'yuki.tanaka@example.com', 'phone' => '555-0110', 'location' => 'Austin, TX', 'type' => 'Seller', 'lead_source' => 'Referral', 'lead_status' => 'Qualified', 'notes' => 'Downsizing after kids moved out.', 'client_since' => now()->subDays(20)],
        ];

        foreach ($clients as $data) {
            Client::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['agent_id' => $agent->id])
            );
        }
    }
}