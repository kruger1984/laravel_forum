<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Image;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(),
            'user_id' => User::factory(),
            'message_id' => Message::factory(),

        ];
    }
}
