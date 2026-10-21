<?php

declare(strict_types=1);

use App\Events\StoreNotificationEvent;
use App\Models\Complaint;
use App\Models\Message;
use App\Models\User;

test('user can store a complaint', function (): void {
    Event::fake([
        StoreNotificationEvent::class,
    ]);

    $user = User::factory()->create();
    $message = Message::factory()->create();

    $data = [
        'body' => fake()->sentence(),
        'theme_id' => $message->theme_id,
    ];

    $response = $this->actingAs($user)
        ->post(route('messages.complaints.store', $message), $data);

    $response->assertOk();

    $response->assertJson([
        'is_not_solved_complaint' => true,
    ]);

    $this->assertDatabaseHas('complaints', [
        'user_id' => $user->id,
        'message_id' => $message->id,
        'body' => $data['body'],
    ]);

    $this->assertDatabaseHas('notifications', [
        'user_id' => $message->user_id,
    ]);

    Event::assertDispatched(StoreNotificationEvent::class);
});

test('store complaint requires body and theme', function (): void {
    $user = User::factory()->create();
    $message = Message::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('messages.complaints.store', $message), []);

    $response->assertStatus(302);

    $response->assertSessionHasErrors(['body', 'theme_id']);
});

test('admin can toggle complaint solved', function (): void {
    Event::fake([
        StoreNotificationEvent::class,
    ]);

    $admin = userAsAdmin();
    $complaint = Complaint::factory()->create();

    $response = $this->actingAs($admin)->patch(route('admin.complaints.update', $complaint));

    $this->assertDatabaseHas('complaints', [
        'id' => $complaint->id,
        'is_solved' => true,
    ]);

    $complaint->refresh();

    $response->assertJson([
        'is_solved' => $complaint->is_solved,
    ]);
});

test('toggling complaint creates a notification', function (): void {
    Event::fake([
        StoreNotificationEvent::class,
    ]);

    $admin = userAsAdmin();
    $complaint = Complaint::factory()->create();

    $this->actingAs($admin)->patch(route('admin.complaints.update', $complaint));

    Event::assertDispatched(StoreNotificationEvent::class);

    $this->assertDatabaseHas('notifications', [
        'user_id' => $complaint->user->id,
        'is_read' => false,
    ]);
});
