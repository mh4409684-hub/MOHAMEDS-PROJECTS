<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get user's notifications
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $user = auth()->user();
        $count = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get unread notifications
     */
    public function unread()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->limit(10)
            ->get();

        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Mark notification as read
     */
    public function markRead(Notification $notification)
    {
        $user = auth()->user();

        if ($notification->user_id !== $user->id) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        $user = auth()->user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Clear old notifications
     */
    public function clearOld()
    {
        $user = auth()->user();

        Notification::where('user_id', $user->id)
            ->where('is_read', true)
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        return response()->json(['success' => true, 'message' => 'Old notifications cleared']);
    }

    /**
     * Delete notification
     */
    public function delete(Notification $notification)
    {
        $user = auth()->user();

        if ($notification->user_id !== $user->id) {
            abort(403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
