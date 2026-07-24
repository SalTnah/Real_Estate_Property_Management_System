<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user)
    {
        return $user->agent !== null; // admin has no appointment access per current scope
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->agent?->id === $appointment->agent_id;
    }

    public function create(User $user)
    {
        return $user->agent !== null;
    }

    public function update(User $user, Appointment $appointment)
    {
        return $user->agent?->id === $appointment->agent_id;
    }

    public function delete(User $user, Appointment $appointment)
    {
        return $user->agent?->id === $appointment->agent_id;
    }
}