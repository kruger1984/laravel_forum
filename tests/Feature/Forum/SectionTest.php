<?php

declare(strict_types=1);

use App\Models\Branch;
use App\Models\Section;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated user can view sections index', function (): void {
    $user = User::factory()->create();

    $sections = Section::factory(5)->create();

    $parentBranches = Branch::factory(3)->for($sections->first())->create();
    Branch::factory(2)->nested($parentBranches->first())->for($sections->first())->create();

    $response = $this->actingAs($user)
        ->get(route('sections.index'));

    $response->assertOk();

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Section/Index')
        ->has('sections')
        ->has('sections', 5)
    );
});

test('soft deleted section is hidden from index', function (): void {
    $user = userAsEditor();

    $sections = Section::factory(5)->create();

    $response = $this->actingAs($user)
        ->delete(route('sections.destroy', ['section' => $sections->first()]));

    $response = $this->actingAs($user)
        ->get(route('sections.index'));

    $response->assertOk();

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Section/Index')
        ->has('sections')
        ->has('sections', 4)
    );

    expect(Section::withTrashed()->count())->toBe(5);
});

test('store section is idempotent by title', function (): void {
    $user = userAsEditor();

    $title = fake()->sentence();

    Section::factory([
        'title' => $title,
    ])->create();

    $response = $this->actingAs($user)
        ->post(route('sections.store'), [
            'title' => $title,
        ]);

    $response->assertRedirect(route('sections.index'));

    expect(Section::count())->toBe(1);
});
