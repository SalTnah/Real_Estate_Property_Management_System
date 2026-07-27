<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAgentController extends Controller
{
    public function index(Request $request)
    {
        $query = Agent::with('user')->withCount('clients');

        if ($request->filled('q')) {
            $keyword = $request->string('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('f_name', 'like', "%{$keyword}%")
                    ->orWhere('l_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('agency_name', 'like', "%{$keyword}%");
            });
        }

        $agents = $query->get();

        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone_num' => 'nullable|string|max:20',
            'agency_name' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['f_name'] . ' ' . $validated['l_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'agent',
        ]);

        Agent::create([
            'user_id' => $user->id,
            'f_name' => $validated['f_name'],
            'l_name' => $validated['l_name'],
            'email' => $validated['email'],
            'phone_num' => $validated['phone_num'] ?? null,
            'agency_name' => $validated['agency_name'] ?? null,
            'license' => $validated['license'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent account created.');
    }

    public function show(Agent $agent)
    {
        $agent->load('clients', 'properties');
        return view('admin.agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $agent->user_id,
            'phone_num' => 'nullable|string|max:20',
            'agency_name' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $agent->update($validated);
        $agent->user->update([
            'name' => $validated['f_name'] . ' ' . $validated['l_name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('admin.agents.show', $agent)
            ->with('success', 'Agent updated.');
    }

    public function destroy(Agent $agent)
    {
        $agent->user()->delete(); // cascades to agent via FK onDelete('cascade')
        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent account removed.');
    }
}