<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->withCount('reactions')
            ->with('reactions', fn ($reaction) => $reaction->where('user_id', auth()->id()))
            ->latest('updated_at')
            ->paginate(20);

        return Inertia::render('Home', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'posts' => PostResource::collection($posts),
        ]);
    }
}
