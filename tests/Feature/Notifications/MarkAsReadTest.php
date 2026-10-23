<?php

declare(strict_types=1);

use App\Models\Notification;
use App\Models\User;

test('user can mark own notifications as read', function (): void {
    $user = User::factory()->create();

    $notification = Notification::factory()->for($user)->create([
        'is_read' => false,
    ]);

    expect($user->notifications()->where('is_read', false)->count())->toBe(1);

    $response = $this->actingAs($user)->patch('/notifications/update_collection', [
        'ids' => [$notification->id],
    ]);

    $response->assertOk();

    $notification->refresh();

    expect($notification->is_read)->toBeTrue()
        ->and($user->notifications()->where('is_read', false)->count())->toBe(0);
});

test('user cannot mark another users notifications as read', function (): void {
    $owner = User::factory()->create();
    $maliciousUser = User::factory()->create();

    $notification = Notification::factory()->for($owner)->create([
        'is_read' => false,
    ]);

    $response = $this->actingAs($maliciousUser)->patch('/notifications/update_collection', [
        'ids' => [$notification->id],
    ]);

    $response->assertOk();

    $notification->refresh();

    expect($notification->is_read)->toBeFalse();
});
