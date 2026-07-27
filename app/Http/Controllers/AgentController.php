<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function __construct()
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            redirect()->route('admin.settings.edit')->send();
            exit;
        }
    }

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

        if ($agent->wasRecentlyCreated) {
            $this->dispatchAgentNotification($agent, 'agent_created', 'New Agent Profile Created');
        }

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

        if ($agent->wasRecentlyCreated) {
            $this->dispatchAgentNotification($agent, 'agent_created', 'New Agent Profile Created');
        }

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

        if ($agent->wasRecentlyCreated) {
            $this->dispatchAgentNotification($agent, 'agent_created', 'New Agent Profile Created');
        } else {
            $this->dispatchAgentNotification($agent, 'agent_updated', 'Agent Profile Updated');
        }

        return redirect()->route('agent.show')->with('success', 'Profile updated.');
    }

    private function dispatchAgentNotification(Agent $agent, string $type, string $titlePrefix): void
    {
        $agentName = trim(($agent->f_name ?? '') . ' ' . ($agent->l_name ?? '')) ?: 'Agent #' . $agent->id;

        $notificationData = [
            'type' => $type,
            'title' => "{$titlePrefix}: " . $agentName,
            'agent_id' => $agent->id,
            'data' => [
                'agent_id' => $agent->id,
                'title' => $agentName,
                'message' => "Agent profile for '{$agentName}' has been " . ($type === 'agent_created' ? 'created.' : 'updated.'),
            ],
        ];

        $recipients = [];
        if ($agent->user_id) {
            $recipients[] = $agent->user_id;
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
    }
}