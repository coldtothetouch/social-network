<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Gleb Moiseev',
            'email' => 'gleb@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123123123'),
            'remember_token' => Str::random(10),
        ]);

        User::factory()->create([
            'name' => fake()->name(),
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123123123'),
            'remember_token' => Str::random(10),
        ]);

        $group = Group::factory()->create([
            'user_id' => $user->id,
        ]);

        GroupUser::factory()->create([
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);

        Post::factory(50)->create([
            'group_id' => $group->id,
        ]);

        Comment::factory(50)->create();
    }
}
