<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'user_id', 'f_name', 'l_name', 'email', 'phone_num',
        'agency_name', 'license', 'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availability()
    {
        return $this->hasMany(AgentAvailability::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
