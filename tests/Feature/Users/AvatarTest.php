<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;

test('user can update avatar', function (): void {
    Storage::fake('public');

    $user = User::factory()->create([
        'avatar' => 'avatars/old-avatar.jpg',
    ]);

    $file = UploadedFile::fake()->image('new-avatar.jpg');

    $response = $this->actingAs($user)->patch('/users/personal', [
        'avatar' => $file,
    ]);

    $user->refresh();

    $response->assertJson([
        'avatar_url' => $user->avatar_url,
    ]);

    Storage::disk('public')->assertExists($user->avatar);
});

test('updating avatar deletes the previous file', function (): void {
    Storage::fake('public');

    $oldFile = UploadedFile::fake()->image('old-avatar.jpg');
    $oldPath = $oldFile->store('avatars', 'public'); // или то место, куда твой контроллер сохраняет

    $user = User::factory()->create([
        'avatar' => $oldPath,
    ]);

    Storage::disk('public')->assertExists($oldPath);

    $newFile = UploadedFile::fake()->image('new-avatar.jpg');

    $this->actingAs($user)->patch('/users/personal', [
        'avatar' => $newFile,
    ]);

    $user->refresh();

    expect($user->avatar)->not->toBe($oldPath);

    Storage::disk('public')->assertMissing($oldPath);

    Storage::disk('public')->assertExists($user->avatar);
});
