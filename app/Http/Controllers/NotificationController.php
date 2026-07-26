<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->agent) {
            $notifications = $user->agent->notifications()->latest()->get();
        } else {
            $notifications = Notification::latest()->get();
        }

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        $user = auth()->user();

        if ($user->agent) {
            abort_unless($notification->agent_id === $user->agent->id, 403);
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
            Notification::unread()->update(['read_at' => now()]);
        }

        return redirect()->back();
    }

    public function destroy(Notification $notification)
    {
        $user = auth()->user();

        if ($user->agent) {
            abort_unless($notification->agent_id === $user->agent->id, 403);
        }

        $notification->delete();

        return redirect()->route('notifications.index');
    }
}