<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        if ($request->wantsJson()) {
            return PostResource::collection($posts);
        }

        $groups = auth()->user()->groups()->with('authGroupUser')->orderBy('role')->get();

        return Inertia::render('Home', [
            'posts' => PostResource::collection($posts),
            'groups' => GroupResource::collection($groups),
        ]);
    }
}
