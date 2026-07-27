<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Client::class);

        $query = auth()->user()->isAdmin()
            ? Client::with('agent')
            : auth()->user()->agent->clients();

        if ($request->filled('q')) {
            $keyword = $request->string('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('f_name', 'like', "%{$keyword}%")
                    ->orWhere('l_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $clients = $query->get();

        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Client::class);

        $validated = $request->validate([
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

        $client = auth()->user()->agent->clients()->create($validated);

        $clientName = trim(($client->f_name ?? '') . ' ' . ($client->l_name ?? '')) ?: 'Client #' . $client->id;

        $notificationData = [
            'type' => 'client_added',
            'title' => 'New Client Added: ' . $clientName,
            'agent_id' => $client->agent_id ?? auth()->user()->agent?->id,
            'data' => [
                'client_id' => $client->id,
                'title' => $clientName,
                'message' => "A new client '{$clientName}' has been added.",
            ],
        ];

        $recipients = [];
        $agentObj = $client->agent ?? auth()->user()->agent;
        if ($agentObj && isset($agentObj->user_id)) {
            $recipients[] = $agentObj->user_id;
        }
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        $recipients = array_unique(array_merge($recipients, $adminIds));

        if (!empty($recipients)) {
            foreach ($recipients as $userId) {
                $notificationData['user_id'] = $userId;
                Notification::create($notificationData);
            }
        } else {
            Notification::create($notificationData);
        }

        return redirect()->route('agent.clients.show', $client)->with('success', 'Client added.');
    }

    public function show(Client $client)
    {
        $this->authorize('view', $client);

        $client->load('agent', 'preference', 'appointments');

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $this->authorize('update', $client);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $validated = $request->validate([
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

        return redirect()->route('agent.clients.show', $client)->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $client->delete();

        return redirect()->route('agent.clients.index');
    }
}