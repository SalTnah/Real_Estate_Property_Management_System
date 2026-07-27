<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Notification;
use App\Traits\HandlesClientPreferences;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    use HandlesClientPreferences;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Client::class);

        $query = auth()->user()->agent->clients();

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

    public function create()
    {
        $this->authorize('create', Client::class);

        return view('clients.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Client::class);

        $validated = $request->validate($this->clientRules());
        $preferenceData = $this->validatedPreferenceData($request);

        $client = DB::transaction(function () use ($validated, $preferenceData) {
            $client = auth()->user()->agent->clients()->create($validated);

            if ($preferenceData) {
                $client->preference()->create($preferenceData);
            }

            return $client;
        });

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

        $client->load('preference');

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $validated = $request->validate($this->clientRules());
        $preferenceData = $this->validatedPreferenceData($request);

        DB::transaction(function () use ($validated, $preferenceData, $client) {
            $client->update($validated);

            if ($preferenceData) {
                $client->preference()->updateOrCreate(['client_id' => $client->id], $preferenceData);
            }
        });

        return redirect()->route('agent.clients.show', $client)->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);

        $actor = auth()->user();
        $actorLabel = $actor->agent ? "{$actor->agent->f_name} {$actor->agent->l_name}" : $actor->name;
        $clientName = "{$client->f_name} {$client->l_name}";

        // Notify all admins
        Notification::create([
            'agent_id' => null,
            'type' => 'client',
            'title' => 'Client deleted',
            'body' => "{$clientName} was deleted by {$actorLabel}.",
            'link' => null,
        ]);

        $client->delete();

        return redirect()->route('agent.clients.index');
    }

    protected function clientRules(): array
    {
        return [
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
        ];
    }
}