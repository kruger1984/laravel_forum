<?php

declare(strict_types=1);

use App\Events\StoreNotificationEvent;
use App\Jobs\ProcessMessageJob;
use App\Models\Image;
use App\Models\Theme;
use App\Models\User;

test('guest cannot store a message', function (): void {
    $this->actingAsGuest();
    $theme = Theme::factory()->create();

    $response = $this->post('/messages', [
        'theme_id' => $theme->id,
        'content' => 'test',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('user can store a message', function (): void {
    Queue::fake();

    $user = User::factory()->create();
    $theme = Theme::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('messages.store'), [
            'theme_id' => $theme->id,
            'content' => 'Hello world',
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('messages', [
        'user_id' => $user->id,
        'theme_id' => $theme->id,
        'content' => 'Hello world',
    ]);

    Queue::assertPushed(ProcessMessageJob::class);
});

test('store message requires content and valid theme', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('messages.store'), [
            'theme_id' => 99999,
            'content' => '',
        ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['theme_id', 'content']);

    $this->assertDatabaseMissing('messages', [
        'user_id' => $user->id,
    ]);
});

test('mention creates answer pivot and notification', function (): void {
    Event::fake([StoreNotificationEvent::class]);

    $author = User::factory()->create();
    $mentionedUser = User::factory()->create();
    $theme = Theme::factory()->create();

    $content = 'Привет, @'.$mentionedUser->id.', посмотри этот пост';

    $response = $this->actingAs($author)
        ->postJson(route('messages.store'), [
            'theme_id' => $theme->id,
            'content' => $content,
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('notifications', [
        'user_id' => $mentionedUser->id,
    ]);

    Event::assertDispatched(StoreNotificationEvent::class);
});

test('invalid mention does not create a notification', function (): void {
    Event::fake([StoreNotificationEvent::class]);

    $author = User::factory()->create();
    $theme = Theme::factory()->create();

    $response = $this->actingAs($author)
        ->postJson(route('messages.store'), [
            'theme_id' => $theme->id,
            'content' => 'Привет, @999999!',
        ]);

    $response->assertOk();

    $this->assertDatabaseMissing('notifications', [
        'title' => 'You were mentioned',
    ]);

    Event::assertNotDispatched(StoreNotificationEvent::class);
});

test('img id in content links image to message', function (): void {
    $user = User::factory()->create();
    $theme = Theme::factory()->create();

    $image = Image::factory()->create([
        'user_id' => $user->id,
        'message_id' => null,
    ]);

    $content = "Сообщение с картинкой img_id={$image->id}";

    $response = $this->actingAs($user)
        ->postJson(route('messages.store'), [
            'theme_id' => $theme->id,
            'content' => $content,
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('images', [
        'id' => $image->id,
        'message_id' => $response->json('id'),
    ]);
});
