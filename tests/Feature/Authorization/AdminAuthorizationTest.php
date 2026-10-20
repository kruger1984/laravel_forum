<?php

declare(strict_types=1);

use App\Models\User;

test('any authenticated user can open admin dashboard', function (): void {

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin');

    $response->assertStatus(200);
});
