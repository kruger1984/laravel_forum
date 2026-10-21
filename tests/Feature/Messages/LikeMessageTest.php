<?php

declare(strict_types=1);

use App\Events\StoreLikeEvent;
use App\Events\StoreNotificationEvent;
use App\Models\Message;
use App\Models\User;

test('user can like a message', function (): void {
    Event::fake([
        StoreNotificationEvent::class,
        StoreLikeEvent::class,
    ]);

    $user = User::factory()->create();
    $message = Message::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('messages.likes.toggle', $message));

    $response->assertOk();

    $this->assertDatabaseHas('message_user_likes', [
        'user_id' => $user->id,
        'message_id' => $message->id,
    ]);

    Event::assertDispatched(StoreNotificationEvent::class);

    Event::assertDispatched(StoreLikeEvent::class);
});

test('user can unlike a message', function (): void {
    Event::fake([
        StoreNotificationEvent::class,
        StoreLikeEvent::class,
    ]);

    $user = User::factory()->create();
    $message = Message::factory()->create();

    $message->likedUsers()->toggle($user->id);

    $response = $this->actingAs($user)
        ->post(route('messages.likes.toggle', $message));

    $response->assertOk();

    $this->assertDatabaseMissing('message_user_likes', [
        'user_id' => $user->id,
        'message_id' => $message->id,
    ]);

    Event::assertNotDispatched(StoreNotificationEvent::class);

    Event::assertDispatched(StoreLikeEvent::class);
});

test('like notifies author only when attached', function (): void {
    Event::fake([
        StoreNotificationEvent::class,
        StoreLikeEvent::class,
    ]);

    $author = User::factory()->create();
    $liker = User::factory()->create();

    $message = Message::factory()->create([
        'user_id' => $author->id,
    ]);

    $response = $this->actingAs($liker)
        ->post(route('messages.likes.toggle', $message));

    $response->assertOk();

    $this->assertDatabaseHas('notifications', [
        'user_id' => $author->id,
        'title' => 'You have liked',
    ]);

    Event::assertDispatched(StoreNotificationEvent::class);
});
