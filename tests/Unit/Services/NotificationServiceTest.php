<?php

declare(strict_types=1);

use App\Events\StoreNotificationEvent;
use App\Models\Message;
use App\Service\NotificationService;

test('stores notification with theme show url and message anchor', function (): void {
    Event::fake([StoreNotificationEvent::class]);

    $message = Message::factory()->create();
    $title = fake()->sentence();
    $url = route('themes.show', $message->theme_id).'#'.$message->id;

    NotificationService::store($message, $title);

    $this->assertDatabaseHas('notifications', [
        'title' => $title,
        'user_id' => $message->user_id,
        'url' => $url,
    ]);

    Event::assertDispatched(StoreNotificationEvent::class);
});
