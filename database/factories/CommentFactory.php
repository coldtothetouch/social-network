<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $post = Post::query()->inRandomOrder()->first();

        $comments = $post->comments;
        $parentId = $comments->isEmpty() ? null : $comments->pluck('id')->random();

        return [
            'body' => $this->faker->word(),
            'post_id' => $post->id,
            'parent_id' => $parentId,
            'user_id' => User::all()->pluck('id')->random(),

            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
