<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $post = Post::query()->create($data);

        return back();
    }

    public function update(Post $post, UpdatePostRequest $request): RedirectResponse
    {
        $post->update($request->validated());
        return back();
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            return response("You don't have permission to delete this post", 403);
        }

        $post->delete();
        return back();
    }
}
