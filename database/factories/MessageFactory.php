<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Message;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence(),
            'theme_id' => Theme::factory(),
            'user_id' => User::factory(),
        ];
    }
}
