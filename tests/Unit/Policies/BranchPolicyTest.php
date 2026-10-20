<?php

declare(strict_types=1);

use App\Models\Branch;
use App\Models\Role;
use App\Models\Section;
use App\Models\User;
use App\Policies\BranchPolicy;

test('plain user cannot update branch', function (): void {
    $policy = new BranchPolicy;
    $user = User::factory()->create();
    $branch = Branch::factory()->create();

    expect($policy->update($user, $branch))->toBeFalse();
});

test('global admin and editor can update branch', function (): void {
    $policy = new BranchPolicy;
    $branch = Branch::factory()->create();
    $admin = userAsAdmin();
    $editor = userAsEditor();

    expect($policy->update($admin, $branch))->toBeTrue()
        ->and($policy->update($editor, $branch))->toBeTrue();
});

test('section editor can update branch in their section', function (): void {
    $policy = new BranchPolicy;

    $section = Section::factory()->create();
    $branch = Branch::factory()->for($section)->create();

    $sectionEditorRole = Role::factory()->sectionEditor($section)->create();
    $user = User::factory()->create();
    $user->roles()->attach($sectionEditorRole);

    expect($policy->update($user, $branch))->toBeTrue();
});

test('section editor cannot update branch in another section', function (): void {
    $policy = new BranchPolicy;

    $sectionA = Section::factory()->create();
    $sectionB = Section::factory()->create();
    $branchB = Branch::factory()->for($sectionB)->create();

    $sectionAEditorRole = Role::factory()->sectionEditor($sectionA)->create();
    $user = User::factory()->create();
    $user->roles()->attach($sectionAEditorRole);

    expect($policy->update($user, $branchB))->toBeFalse();
});

test('branch editor can update only their specific branch', function (): void {
    $policy = new BranchPolicy;

    $branch = Branch::factory()->create();
    $otherBranch = Branch::factory()->create();

    $branchEditorRole = Role::factory()->branchEditor($branch)->create();
    $user = User::factory()->create();
    $user->roles()->attach($branchEditorRole);

    expect($policy->update($user, $branch))->toBeTrue()
        ->and($policy->update($user, $otherBranch))->toBeFalse();
});
