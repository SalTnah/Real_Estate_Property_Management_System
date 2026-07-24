<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id', 'title', 'description', 'property_type', 'status',
        'street_address', 'city', 'state', 'zip', 'latitude', 'longitude',
        'price', 'size_sqft', 'lot_acre', 'bedrooms', 'bathrooms',
        'year_built', 'is_favorited',
    ];

    protected $casts = [
        'is_favorited' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'price' => 'decimal:2',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function photos()
    {
        return $this->hasMany(PropertyPhoto::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}