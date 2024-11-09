<?php

namespace App\Http\Controllers;

use App\Http\Resources\Group\FeedGroupResource;
use App\Http\Resources\Post\FeedPostResource;
use App\Http\Resources\User\FeedUserResource;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __invoke(Request $request, string $search = null)
    {
        if (!$search) {
            return redirect()->route('home');
        }

        $publicGroupIds = Group::query()
            ->where('private', 0)
            ->get()
            ->pluck('id');

        $posts = Post::query()
            ->with([
                'user', 'group.authGroupUser', 'attachments', 'reactions',
                'comments' => function ($query) {
                    $query->with(['reactions', 'user'])->withCount('likes');
                }
            ])
            ->withCount('reactions')
            ->where('body', 'like', "%$search%")
            ->where(function ($query) use ($publicGroupIds) {
                $query->whereIn('group_id', $publicGroupIds)
                    ->orWhereIn('group_id', auth()->user()->groups->pluck('id'));
            })
            ->latest()
            ->paginate(5);

        if ($request->wantsJson()) {
            return FeedPostResource::collection($posts);
        }

        $users = User::query()
            ->where('name', 'like', "%$search%")
            ->orWhere('username', 'like', "%$search%")
            ->get();

        $groups = Group::query()
            ->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->get();

        return Inertia::render('Search', [
            'search' => $search,
            'users' => FeedUserResource::collection($users),
            'posts' => FeedPostResource::collection($posts),
            'groups' => FeedGroupResource::collection($groups),
        ]);
    }
}
