<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\Message;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->sentence(),
            'user_id' => User::factory(),
            'message_id' => Message::factory(),
            'theme_id' => Theme::factory(),
        ];
    }

    public function solved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_solved' => true,
        ]);
    }
}
