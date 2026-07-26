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
        $agents = [
            ['name' => 'Dana Alvarez', 'f_name' => 'Dana', 'l_name' => 'Alvarez', 'email' => 'dana@estate.test', 'agency_name' => 'Skyline Realty'],
            ['name' => 'Marcus Chen', 'f_name' => 'Marcus', 'l_name' => 'Chen', 'email' => 'marcus.chen@estate.test', 'agency_name' => 'Horizon Properties'],
            ['name' => 'Isabelle Ferrer', 'f_name' => 'Isabelle', 'l_name' => 'Ferrer', 'email' => 'isabelle.ferrer@estate.test', 'agency_name' => 'Golden Gate Homes'],
            ['name' => 'Trevor Doyle', 'f_name' => 'Trevor', 'l_name' => 'Doyle', 'email' => 'trevor.doyle@estate.test', 'agency_name' => 'Lakeside Realty Group'],
            ['name' => 'Ayesha Malik', 'f_name' => 'Ayesha', 'l_name' => 'Malik', 'email' => 'ayesha.malik@estate.test', 'agency_name' => 'Summit Real Estate'],
            ['name' => 'Nolan Brecht', 'f_name' => 'Nolan', 'l_name' => 'Brecht', 'email' => 'nolan.brecht@estate.test', 'agency_name' => 'Cornerstone Properties'],
            ['name' => 'Renata Costa', 'f_name' => 'Renata', 'l_name' => 'Costa', 'email' => 'renata.costa@estate.test', 'agency_name' => 'Blue Ridge Realty'],
            ['name' => 'Julian Okafor', 'f_name' => 'Julian', 'l_name' => 'Okafor', 'email' => 'julian.okafor@estate.test', 'agency_name' => 'Metro Living Group'],
            ['name' => 'Sabine Hoffman', 'f_name' => 'Sabine', 'l_name' => 'Hoffman', 'email' => 'sabine.hoffman@estate.test', 'agency_name' => 'Willow Creek Homes'],
            ['name' => 'Desmond Foster', 'f_name' => 'Desmond', 'l_name' => 'Foster', 'email' => 'desmond.foster@estate.test', 'agency_name' => 'Pinnacle Realty Partners'],
        ];

        foreach ($agents as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'agent',
                    'email_verified_at' => now(),
                ]
            );

            Agent::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'f_name' => $data['f_name'],
                    'l_name' => $data['l_name'],
                    'email' => $data['email'],
                    'agency_name' => $data['agency_name'],
                ]
            );
        }
    }
}