<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $notifications = $user->notifications()->take(10)->get();
        $unreadCount = $user->notifications()->where('is_read', false)->count();

        $notifications = $notifications->map(function ($n) use ($user) {
            $url = $n->complaint_id ? $this->complaintUrl($user->role, $n->complaint_id) : null;

            return [
                'id'            => $n->id,
                'title'         => $n->title,
                'message'       => $n->message,
                'is_read'       => $n->is_read,
                'created_human' => $n->created_at->diffForHumans(),
                'url'           => $url,
            ];
        });

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['status' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['status' => true]);
    }

    private function complaintUrl(string $role, int $complaintId): string
    {
        return match ($role) {
            'admin' => route('admin.complaints.show', $complaintId),
            'staff' => route('staff.complaints.show', $complaintId),
            default => route('employee.complaints.show', $complaintId),
        };
    }
}