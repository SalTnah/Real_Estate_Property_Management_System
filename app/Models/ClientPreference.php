<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPreference extends Model
{
    protected $fillable = [
        'client_id', 'budget_min', 'budget_max', 'pref_property_type',
        'pref_bedrooms', 'pref_bathrooms', 'pref_areas', 'must_haves',
        'additional_notes',
    ];

    protected $casts = [
        'pref_areas' => 'array',
        'must_haves' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}