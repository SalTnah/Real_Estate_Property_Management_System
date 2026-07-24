<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentAvailability extends Model
{
    protected $fillable = [
        'agent_id', 'days_of_week', 'start_time', 'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}