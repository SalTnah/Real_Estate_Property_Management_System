<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = $user->agent
            ? $user->agent->notifications()->latest()->get()
            : Notification::forAdmins()->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        $user = auth()->user();

        if ($user->agent) {
            abort_unless($notification->agent_id === $user->agent->id, 403);
        } else {
            abort_unless(is_null($notification->agent_id), 403);
        }

        $notification->markAsRead();

        return redirect()->back();
    }

    public function markAllRead()
    {
        $user = auth()->user();

        if ($user->agent) {
            $user->agent->notifications()->unread()->update(['read_at' => now()]);
        } else {
            Notification::forAdmins()->unread()->update(['read_at' => now()]);
        }

        return redirect()->back();
    }

    public function clearRead()
    {
        $user = auth()->user();

        if ($user->agent) {
            $user->agent->notifications()->whereNotNull('read_at')->delete();
        } else {
            Notification::forAdmins()->whereNotNull('read_at')->delete();
        }

        return redirect()->back()->with('success', 'Read notifications cleared.');
    }

    public function destroy(Notification $notification)
    {
        $user = auth()->user();

        if ($user->agent) {
            abort_unless($notification->agent_id === $user->agent->id, 403);
        } else {
            abort_unless(is_null($notification->agent_id), 403);
        }

        $notification->delete();

        return redirect()->route('notifications.index');
    }
}