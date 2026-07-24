<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedSearch extends Model
{
    protected $fillable = [
        'client_id', 'search_name', 'criteria_summary', 'alert_frequency',
        'alerts_enabled', 'new_matches_count',
    ];

    protected $casts = [
        'alerts_enabled' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}