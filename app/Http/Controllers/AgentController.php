<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        
        $agent = $user->agent ?? Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'f_name' => $user->name,
                'l_name' => '',
                'email' => $user->email,
            ]
        );

        return view('agent.show', compact('agent'));
    }

    public function edit()
    {
        $user = auth()->user();
        
        $agent = $user->agent ?? Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'f_name' => $user->name,
                'l_name' => '',
                'email' => $user->email,
            ]
        );

        return view('agent.edit', compact('agent'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $agent = $user->agent ?? Agent::firstOrCreate(
            ['user_id' => $user->id],
            [
                'f_name' => $user->name,
                'l_name' => '',
                'email' => $user->email,
            ]
        );

        $validated = $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_num' => 'nullable|string|max:20',
            'agency_name' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $agent->update($validated);

        return redirect()->route('agent.show')->with('success', 'Profile updated.');
    }
}