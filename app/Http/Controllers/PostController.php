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

    public function update(UpdatePostRequest $request)
    {

    }
}
