<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'agent_id', 'f_name', 'l_name', 'email', 'phone', 'location',
        'type', 'lead_source', 'lead_status', 'last_activity',
        'notes', 'client_since',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'client_since' => 'date',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function preference()
    {
        return $this->hasOne(ClientPreference::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function savedSearches()
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }
}