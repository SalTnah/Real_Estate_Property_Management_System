<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with('agent');

        if ($request->filled('q')) {
            $keyword = $request->string('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('f_name', 'like', "%{$keyword}%")
                    ->orWhere('l_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $clients = $query->get();

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        $agents = Agent::orderBy('f_name')->orderBy('l_name')->get();

        return view('admin.clients.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'f_name' => 'required|string|max:191',
            'l_name' => 'required|string|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:191',
            'location' => 'nullable|string|max:191',
            'type' => 'required|in:Buyer,Seller,Both,Renter',
            'lead_source' => 'nullable|string|max:191',
            'lead_status' => 'required|in:New,Contacted,Qualified,Nurturing,Client,Closed,Lost',
            'notes' => 'nullable|string',
            'client_since' => 'nullable|date',
        ]);

        $agent = Agent::findOrFail($validated['agent_id']);
        unset($validated['agent_id']);

        $client = $agent->clients()->create($validated);

        return redirect()->route('admin.clients.show', $client)->with('success', 'Client added.');
    }

    public function show(Client $client)
    {
        $client->load('agent', 'preference', 'appointments');

        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $agents = Agent::orderBy('f_name')->orderBy('l_name')->get();

        return view('admin.clients.edit', compact('client', 'agents'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'f_name' => 'required|string|max:191',
            'l_name' => 'required|string|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:191',
            'location' => 'nullable|string|max:191',
            'type' => 'required|in:Buyer,Seller,Both,Renter',
            'lead_source' => 'nullable|string|max:191',
            'lead_status' => 'required|in:New,Contacted,Qualified,Nurturing,Client,Closed,Lost',
            'notes' => 'nullable|string',
            'client_since' => 'nullable|date',
        ]);

        $client->update($validated);

        return redirect()->route('admin.clients.show', $client)->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $actor = auth()->user();
        $clientName = "{$client->f_name} {$client->l_name}";

        // Notify the owning agent that an admin removed their client
        if ($client->agent_id) {
            Notification::create([
                'agent_id' => $client->agent_id,
                'type' => 'client',
                'title' => 'Client deleted',
                'body' => "{$clientName} was deleted by Admin ({$actor->name}).",
                'link' => null,
            ]);
        }

        // Notify other admins too
        Notification::create([
            'agent_id' => null,
            'type' => 'client',
            'title' => 'Client deleted',
            'body' => "{$clientName} was deleted by Admin ({$actor->name}).",
            'link' => null,
        ]);

        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client removed.');
    }
}