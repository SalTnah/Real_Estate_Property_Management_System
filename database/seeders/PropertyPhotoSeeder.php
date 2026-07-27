<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Database\Seeder;

class PropertyPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::all();

        foreach ($properties as $property) {
            PropertyPhoto::create([
                'property_id' => $property->id,
                'photo_url' => "https://picsum.photos/seed/property{$property->id}-1/800/600",
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            PropertyPhoto::create([
                'property_id' => $property->id,
                'photo_url' => "https://picsum.photos/seed/property{$property->id}-2/800/600",
                'is_primary' => false,
                'sort_order' => 1,
            ]);
        }
    }
}