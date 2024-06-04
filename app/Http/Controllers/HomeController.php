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
    public function index(): Response
    {
        $posts = Post::query()
            ->withCount(['reactions', 'comments'])
            ->with([
                'user', 'group', 'attachments',
                'reactions' => function ($query) {
                    $query->where('user_id', auth()->id());
                },
                'comments' => function ($query) {
                    $query->withCount('likes')
                        ->withCount('comments')
                        ->whereNull('parent_id')
                        ->with([
                            'user', 'comments',
                            'reactions' => function ($query) {
                                $query->where('user_id', auth()->id());
                            }
                        ]);
                }
            ])
            ->latest('updated_at')
            ->paginate(20);

        return Inertia::render('Home', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'posts' => PostResource::collection($posts),
        ]);
    }
}
