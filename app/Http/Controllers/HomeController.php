<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
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
        });
        dd($postComments);*/

        $posts = Post::query()
            ->withCount(['reactions', 'comments'])
            ->with([
                'attachments', 'group', 'user',
                'reactions' => function ($query) {
                    $query->where('user_id', auth()->id());
                },
                'comments' => function ($query) {
                    $query->withCount('likes');
                }
            ])
            ->feed()
            ->latest('updated_at')
            ->paginate(20);

       // dd($posts->toRawSql());

        if ($request->wantsJson()) {
            return PostResource::collection($posts);
        }

        $groups = auth()->user()->groups()->orderBy('role')->get();
        $followings = auth()->user()->followings;

        return Inertia::render('Home', [
            'posts' => PostResource::collection($posts),
            'groups' => GroupResource::collection($groups),
            'followings' => UserResource::collection($followings),
        ]);
    }
}
