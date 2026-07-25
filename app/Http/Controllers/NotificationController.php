<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->agent->notifications()->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        abort_unless($notification->agent_id === auth()->user()->agent->id, 403);

        $notification->markAsRead();

        return redirect()->back();
    }

    public function markAllRead()
    {
        auth()->user()->agent->notifications()->unread()->update(['read_at' => now()]);

        return redirect()->back();
    }

    public function destroy(Notification $notification)
    {
        abort_unless($notification->agent_id === auth()->user()->agent->id, 403);

        $notification->delete();

        return redirect()->route('notifications.index');
    }
}
