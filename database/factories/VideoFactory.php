<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'url' => $this->faker->url(),
            'description' => $this->faker->sentence(),
            'type' => 'youtube',
            'is_published' => 1,
        ];
    }

    public function unpublished(): VideoFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => 0,
            ];
        });
    }
}
