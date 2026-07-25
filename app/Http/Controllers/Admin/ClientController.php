<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::with('agent')->get();

        return view('admin.clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);

        $client->load('agent', 'preference', 'appointments');

        return view('admin.clients.show', compact('client'));
    }
}
