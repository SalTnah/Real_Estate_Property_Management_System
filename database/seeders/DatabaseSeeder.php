<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,

            AgentSeeder::class,
            AgentAvailabilitySeeder::class,

            ClientSeeder::class,
            ClientPreferenceSeeder::class,

            PropertySeeder::class,
            PropertyPhotoSeeder::class,

            AppointmentSeeder::class,
            NotificationSeeder::class,
            SearchSeeder::class,
            SavedSearchSeeder::class,
        ]);
    }
}
