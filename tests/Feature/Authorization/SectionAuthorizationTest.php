<?php

declare(strict_types=1);

use App\Models\Section;
use App\Models\User;

test('guest is redirected from sections index', function (): void {

    $this->actingAsGuest();
    $response = $this->get('/sections');

    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('user without role cannot create section', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('sections.store'), [
            'title' => 'New section',
        ]);

    $response->assertForbidden();
});

test('editor can create section', function (): void {
    $user = userAsEditor();

    $response = $this->actingAs($user)
        ->post(route('sections.store'), [
            'title' => 'New section',
        ]);

    $response->assertRedirect(route('sections.index'));

    $this->assertDatabaseHas('sections', ['title' => 'New section']);
});

test('admin can create section', function (): void {
    $user = userAsAdmin();

    $response = $this->actingAs($user)
        ->post(route('sections.store'), [
            'title' => 'New section',
        ]);

    $response->assertRedirect(route('sections.index'));

    $this->assertDatabaseHas('sections', ['title' => 'New section']);
});

test('authenticated user can update and delete section', function (): void {
    $user = User::factory()->create();
    $section = Section::factory()->create();

    $this->actingAs($user)
        ->put(route('sections.update', $section), [
            'title' => 'Updated Title',
        ])
        ->assertRedirect(route('sections.index'));

    $this->assertDatabaseHas('sections', [
        'id' => $section->id,
        'title' => 'Updated Title',
    ]);

    $this->actingAs($user)
        ->from(route('sections.index'))
        ->delete(route('sections.destroy', $section))
        ->assertRedirect(route('sections.index'));

    $this->assertSoftDeleted($section);
});
