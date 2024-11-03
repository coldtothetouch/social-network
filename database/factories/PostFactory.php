<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'body' => fake()->paragraph(5),
            'user_id' => 1,
            'group_id' => null,
            'deleted_by' => null,
            'deleted_at' => null,
        ];
    }
}
