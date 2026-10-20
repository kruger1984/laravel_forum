<?php

declare(strict_types=1);

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;

test('editor can update branch in scope', function (): void {
    $branch = Branch::factory()->create([
        'title' => 'Test Branch',
    ]);
    $user = User::factory()->create();
    $role = Role::factory()->branchEditor($branch)->create();
    $user->roles()->attach($role);

    $response = $this->actingAs($user)
        ->put(route('branches.update', $branch), [
            'title' => 'Test Branch Updated',
            'section_id' => $branch->section_id,
        ]);

    $response->assertRedirect(route('sections.index'));

    $this->assertDatabaseHas('branches', ['title' => 'Test Branch Updated']);
});

test('user cannot update branch out of scope', function (): void {
    $branch = Branch::factory()->create();
    $otherBranch = Branch::factory()->create();

    $user = User::factory()->create();
    $role = Role::factory()->branchEditor($branch)->create();
    $user->roles()->attach($role);

    $response = $this->actingAs($user)
        ->put(route('branches.update', $otherBranch), [
            'title' => 'Hacked Branch Title',
            'section_id' => $branch->section_id,
        ]);

    $response->assertForbidden();
});

test('user cannot delete branch out of scope', function (): void {
    $branch = Branch::factory()->create();
    $otherBranch = Branch::factory()->create();

    $user = User::factory()->create();
    $role = Role::factory()->branchEditor($branch)->create();
    $user->roles()->attach($role);

    $response = $this->actingAs($user)
        ->delete(route('branches.destroy', $otherBranch));

    $response->assertForbidden();
});
