<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Database\Seeder;

class PropertyPhotoSeeder extends Seeder
{
    public function run(): void
    {
        PropertyPhoto::truncate();

        $placeholders = [
            '/images/properties/house-1.jpg',
            '/images/properties/house-2.jpg',
            '/images/properties/house-3.jpg',
            '/images/properties/house-4.jpg',
        ];

        $properties = Property::all();

        foreach ($properties as $index => $property) {
            PropertyPhoto::create([
                'property_id' => $property->id,
                'photo_url' => asset($placeholders[$index % count($placeholders)]),
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            PropertyPhoto::create([
                'property_id' => $property->id,
                'photo_url' => asset($placeholders[($index + 1) % count($placeholders)]),
                'is_primary' => false,
                'sort_order' => 1,
            ]);
        }
    }
}