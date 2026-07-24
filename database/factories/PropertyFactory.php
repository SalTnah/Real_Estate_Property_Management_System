<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
// database/factories/PropertyFactory.php
    public function definition(): array
    {
        return [
            'agent_id' => \App\Models\Agent::inRandomOrder()->value('id'),
            'title' => fake()->streetName().' '.fake()->randomElement(['Villa', 'Cottage', 'Estate', 'Townhome']),
            'property_type' => fake()->randomElement(['House', 'Condo', 'Townhouse', 'Apartment', 'Land']),
            'status' => fake()->randomElement(['Available', 'Pending', 'Sold']),
            'street_address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip' => fake()->postcode(),
            'price' => fake()->numberBetween(150_000, 2_000_000),
            'size_sqft' => fake()->numberBetween(800, 5000),
            'bedrooms' => fake()->numberBetween(1, 6),
            'bathrooms' => fake()->randomFloat(1, 1, 4),
            'year_built' => fake()->numberBetween(1960, 2024),
        ];
    }
}
