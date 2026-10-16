<?php

declare(strict_types=1);

namespace App\Service;

use App\Events\StoreNotificationEvent;
use App\Models\Message;
use App\Models\Notification;

final class NotificationService
{
    public static function store(Message $message,?int $id,string $title): void
    {
        $id = $id ?? $message->user_id;

        $notification = Notification::create([
            'title' => $title,
            'user_id' => $id,
            'url' => route('themes.show', $message->theme_id).'#'.$message->id,
        ]);

        event(new StoreNotificationEvent($notification));
    }
}
