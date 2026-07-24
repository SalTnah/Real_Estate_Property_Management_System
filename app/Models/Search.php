<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $fillable = [
        'agent_id', 'client_id', 'query_term', 'type', 'location',
        'price_min', 'price_max', 'beds', 'sort', 'filter', 'map_view',
    ];

    protected $casts = [
        'filter' => 'array',
        'map_view' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}