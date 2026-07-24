<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'dana@estate.test'],
            ['name' => 'Dana Alvarez', 'password' => Hash::make('password'), 'role' => 'agent', 'email_verified_at' => now()]
        );

        Agent::firstOrCreate(
            ['user_id' => $user->id],
            ['f_name' => 'Dana', 'l_name' => 'Alvarez', 'email' => $user->email, 'agency_name' => 'Skyline Realty']
        );
    }
}