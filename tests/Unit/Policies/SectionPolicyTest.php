<?php

declare(strict_types=1);

use App\Models\Role;
use App\Models\Section;
use App\Models\User;
use App\Policies\SectionPolicy;

test('plain user cannot create section', function (): void {
    $policy = new SectionPolicy;
    $user = User::factory()->make();

    expect($policy->create($user))->toBeFalse();
});

test('admin can create section', function (): void {
    $policy = new SectionPolicy;

    $adminRole = Role::factory()->admin()->create();
    $user = User::factory()->create();
    $user->roles()->attach($adminRole);

    expect($policy->create($user))->toBeTrue();
});

test('editor can create section', function (): void {
    $policy = new SectionPolicy;

    $editorRole = Role::factory()->editor()->create();
    $user = User::factory()->create();
    $user->roles()->attach($editorRole);

    expect($policy->create($user))->toBeTrue();
});

test('plain user cannot delete section', function (): void {
    $policy = new SectionPolicy;
    $user = User::factory()->make();

    $section = Section::factory()->create();

    expect($policy->delete($user, $section))->toBeFalse();
});

test('admin can delete section', function (): void {
    $policy = new SectionPolicy;

    $adminRole = Role::factory()->admin()->create();
    $user = User::factory()->create();
    $user->roles()->attach($adminRole);
    $section = Section::factory()->create();

    expect($policy->delete($user, $section))->toBeTrue();
});

test('editor can delete section', function (): void {
    $policy = new SectionPolicy;

    $editorRole = Role::factory()->editor()->create();
    $user = User::factory()->create();
    $user->roles()->attach($editorRole);
    $section = Section::factory()->create();

    expect($policy->delete($user, $section))->toBeTrue();
});
