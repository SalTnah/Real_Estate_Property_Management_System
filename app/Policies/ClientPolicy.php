<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user)
    {
        return $user->agent !== null;
    }

    public function view(User $user, Client $client)
    {
        return $user->agent?->id === $client->agent_id;
    }

    public function create(User $user)
    {
        return $user->agent !== null;
    }

    public function update(User $user, Client $client)
    {
        return $user->agent?->id === $client->agent_id;
    }

    public function delete(User $user, Client $client)
    {
        return $user->agent?->id === $client->agent_id;
    }
}