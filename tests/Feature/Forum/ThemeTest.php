<?php

declare(strict_types=1);

use App\Models\Branch;
use App\Models\Theme;

test('user can create a theme', function (): void {
    $user = userAsEditor();
    $branch = Branch::factory()->create();

    $title = fake()->word();
    $description = fake()->sentence();

    $response = $this->actingAs($user)->post(route('themes.store'), [
        'title' => $title,
        'description' => $description,
        'branch_id' => $branch->id,
    ]);

    $response->assertRedirect(route('branches.show', $branch->id));

    $this->assertDatabaseHas('themes', [
        'title' => $title,
        'description' => $description,
        'branch_id' => $branch->id,
    ]);
});

test('user can update a theme', function (): void {
    $user = userAsEditor();
    $branch = Branch::factory()->create();
    $theme = Theme::factory()->for($branch)->create();

    $newTitle = fake()->word();
    $newDescription = fake()->sentence();

    $response = $this->actingAs($user)->put(route('themes.update', $theme), [
        'title' => $newTitle,
        'description' => $newDescription,
    ]);

    $theme->refresh();

    expect($theme->title)->toBe($newTitle)
        ->and($theme->description)->toBe($newDescription);

    $response->assertRedirect(route('branches.show', $branch->id));
});

test('user can soft delete a theme', function (): void {
    $user = userAsEditor();
    $branch = Branch::factory()->create();
    $theme = Theme::factory()->for($branch)->create();

    $response = $this->actingAs($user)->delete(route('themes.destroy', $theme));

    expect($theme->fresh()->trashed())->toBeTrue();

    $response->assertRedirect(route('branches.show', $branch->id));
});
