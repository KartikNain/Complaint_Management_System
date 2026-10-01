<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send a notification to a single user.
     */
    public static function send(int $userId, ?int $complaintId, string $type, string $title, string $message): void
    {
        Notification::create([
            'user_id'      => $userId,
            'complaint_id' => $complaintId,
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
        ]);
    }

    /**
     * Send the same notification to every admin.
     */
    public static function sendToAdmins(?int $complaintId, string $type, string $title, string $message): void
    {
        User::where('role', 'admin')->pluck('id')->each(function ($adminId) use ($complaintId, $type, $title, $message) {
            self::send($adminId, $complaintId, $type, $title, $message);
        });
    }
}