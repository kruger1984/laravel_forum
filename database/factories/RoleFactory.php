<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Role;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker,
            'code' => $this->faker->unique()->slug(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'admin',
            'code' => 'admin',
        ]);
    }

    public function editor(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => 'editor',
            'code' => 'editor',
        ]);
    }

    public function sectionEditor(Section $section): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => "editor.{$section->id}",
            'code' => "editor.{$section->id}",
        ]);
    }

    public function branchEditor(Branch $branch): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => "editor.{$branch->section_id}.{$branch->id}",
            'code' => "editor.{$branch->section_id}.{$branch->id}",
        ]);
    }
}
