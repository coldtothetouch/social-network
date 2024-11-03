<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Laravel Developers',
            'slug' => $this->faker->slug(),
            'description' => $this->faker->text(),
            'cover_path' => null,
            'avatar_path' => null,
            'private' => $this->faker->boolean(),
            'user_id' => null,
            'deleted_by' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
