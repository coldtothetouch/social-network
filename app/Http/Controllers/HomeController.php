<?php

namespace App\Http\Controllers;

use App\Http\Resources\Group\FeedGroupResource;
use App\Http\Resources\Post\FeedPostResource;
use App\Http\Resources\User\FeedUserResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        /*$postComments = [];

        $comments = Post::query()->find(36)->comments;
        $comments->each(function ($comment) use (&$postComments) {
            $postComments[$comment->parent_id ?? 0][] = $comment;
        });*/

        $groups = auth()->user()->groups()->orderBy('role')->get();
        $followings = auth()->user()->followings()->get();

        $posts = Post::query()
            ->with([
                'attachments', 'user', 'reactions', 'group',
                'comments' => function ($query) {
                    $query->with(['reactions', 'user'])->withCount('likes');
                }
            ])
            ->feed($groups, $followings)
            ->latest('updated_at')
            ->paginate(10);

        if ($request->wantsJson()) {
            return FeedPostResource::collection($posts);
        }

        return Inertia::render('Home', [
            'posts' => FeedPostResource::collection($posts),
            'groups' => FeedGroupResource::collection($groups),
            'followings' => FeedUserResource::collection($followings),
        ]);
    }
}
