<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $agent = auth()->user()->agent;

        return view('agent.profile.show', compact('agent'));
    }

    public function edit()
    {
        $agent = auth()->user()->agent;

        return view('agent.profile.edit', compact('agent'));
    }

    public function update(Request $request)
    {
        $agent = auth()->user()->agent;

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

        return redirect()->route('agent.profile.show')->with('success', 'Profile updated.');
    }
}
