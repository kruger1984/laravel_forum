<?php

declare(strict_types=1);

use App\Models\Branch;
use App\Models\Section;
use App\Models\Theme;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('user can store a branch under a section', function (): void {
    $user = userAsEditor();
    $section = Section::factory()->create();

    $title = fake()->sentence;

    $response = $this->actingAs($user)->post(route('branches.store'), [
        'section_id' => $section->id,
        'title' => $title,
    ]);

    $response->assertRedirect(route('sections.index'));

    expect(Branch::first()->title)->toBe($title);
});

test('user can create a nested branch', function (): void {
    $user = userAsEditor();
    $section = Section::factory()->create();

    $branch = Branch::factory()->for($section)->create();

    $title = fake()->sentence;

    $response = $this->actingAs($user)->post(route('branches.store'), [
        'section_id' => $section->id,
        'title' => $title,
        'parent_id' => $branch->id,
    ]);

    $response->assertRedirect(route('sections.index'));

    expect(Branch::where('parent_id', $branch->id)->first()->title)->toBe($title);
});

test('branch show includes children and themes', function (): void {
    $user = User::factory()->create();

    $branch = Branch::factory()->create();
    $branchNested = Branch::factory()->nested($branch)->create();

    $themes = Theme::factory()->create([
        'branch_id' => $branch->id,
    ]);
    $otherThemes = Theme::factory()->create();

    $response = $this->actingAs($user)->get(route('branches.show', $branch));

    $response->assertOk();

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Branch/Show')
        ->has('branch')
        ->has('branch.children', 1)
        ->has('branch.themes', 1)
    );
});

test('section branch index except excludes given branch', function (): void {
    $user = User::factory()->create();
    $section = Section::factory()->create();

    $branchToExclude = Branch::factory()->for($section)->create([
        'title' => 'Excluded Branch',
    ]);

    $otherBranch = Branch::factory()->for($section)->create([
        'title' => 'Other Branch',
    ]);

    $response = $this->actingAs($user)
        ->get("/sections/{$section->id}/branches_except/{$branchToExclude->id}");

    $response->assertOk();

    $response->assertJsonFragment([
        'id' => $otherBranch->id,
        'title' => 'Other Branch',
    ]);

    $response->assertJsonMissing([
        'id' => $branchToExclude->id,
        'title' => 'Excluded Branch',
    ]);
});
