<?php

declare(strict_types=1);

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('user can upload an image', function (): void {
    Storage::fake('public');
    $user = User::factory()->create();

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->actingAs($user)->post(route('images.store'), [
        'image' => $file,
    ]);

    $this->assertDatabaseHas('images', [
        'user_id' => $user->id,
    ]);
    $image = Image::first();

    $response->assertJson([
        'url' => $image->url,
        'id' => $image->id,
    ]);

    Storage::disk('public')->assertExists($image->path);
});

test('upload rejects non image files', function (): void {
    Storage::fake('public');
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('document.txt', 100);

    $response = $this->actingAs($user)->post(route('images.store'), [
        'image' => $file,
    ]);

    $response->assertInvalid(['image']);
});
