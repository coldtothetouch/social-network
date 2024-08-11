<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->withCount(['reactions', 'comments'])
            ->with([
                'user', 'group', 'attachments',
                'reactions' => function ($query) {
                    $query->where('user_id', auth()->id());
                },
                'comments' => function ($query) {
                    $query->withCount('likes');
                }
            ])
            ->latest('updated_at')
            ->paginate(20);

        if($request->wantsJson()) {
            return PostResource::collection($posts);
        }

        return Inertia::render('Home', [
            'posts' => PostResource::collection($posts),
        ]);
    }
}
