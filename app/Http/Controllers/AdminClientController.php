<?php

namespace App\Http\Controllers;

use App\Models\Client;

class AdminClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('agent')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        $client->load('agent', 'preference', 'appointments');
        return view('admin.clients.show', compact('client'));
    }
}