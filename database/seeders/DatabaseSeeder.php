<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,              // your existing admin user

            AgentSeeder::class,               // agents (+ their user accounts)
            AgentAvailabilitySeeder::class,   // depends on agents

            ClientSeeder::class,              // depends on agents
            ClientPreferenceSeeder::class,    // depends on clients

            PropertySeeder::class,            // depends on agents
            PropertyPhotoSeeder::class,       // depends on properties

            AppointmentSeeder::class,         // depends on agents, clients, properties
            SearchSeeder::class,              // depends on agents, clients
            SavedSearchSeeder::class,         // depends on clients
        ]);
    }
}