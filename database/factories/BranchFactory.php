<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'parent_id' => null,
            'title' => $this->faker->sentence(),
        ];
    }

    public function trashed(): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }

    public function nested(Branch $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'section_id' => $parent->section_id,
            'parent_id' => $parent->id,
        ]);
    }
}
