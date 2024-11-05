<?php

namespace App\Http\Controllers;

use App\Enums\GroupUserRole;
use App\Enums\GroupUserStatus;
use App\Http\Enums\ReactionEnum;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Comment;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Post;
use App\Models\Reaction;
use App\Notifications\NewReactionOnComment;
use App\Notifications\NewReactionOnPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReactionController extends Controller
{
    public function commentReaction(Request $request, Comment $comment): JsonResponse
    {
        $data = $request->validate([
            'reaction' => Rule::enum(ReactionEnum::class)
        ]);

        $reaction = Reaction::query()
            ->where('user_id', auth()->id())
            ->where('reactionable_id', $comment->id)
            ->where('reactionable_type', Comment::class)
            ->first();

        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;

            Reaction::query()->create([
                'reactionable_id' => $comment->id,
                'reactionable_type' => Comment::class,
                'user_id' => auth()->id(),
                'type' => $data['reaction']
            ]);

            if (!$comment->isOwnedByAuthUser()) {
                $comment->user->notify(new NewReactionOnComment(auth()->user(), $comment));
            }
        }

        $reactionCount = Reaction::query()
            ->where('reactionable_id', $comment->id)
            ->where('reactionable_type', Comment::class)
            ->count();

        return response()->json([
            'reactions_count' => $reactionCount,
            'current_user_has_reaction' => $hasReaction,
        ]);
    }

    public function postReaction(Request $request, Post $post): JsonResponse
    {
        $data = $request->validate([
            'reaction' => Rule::enum(ReactionEnum::class)
        ]);

        $reaction = Reaction::query()
            ->where('user_id', auth()->id())
            ->where('reactionable_id', $post->id)
            ->where('reactionable_type', Post::class)
            ->first();

        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;

            Reaction::query()->create([
                'reactionable_id' => $post->id,
                'reactionable_type' => Post::class,
                'user_id' => auth()->id(),
                'type' => $data['reaction']
            ]);

            if (!$post->isOwnedByAuthUser()) {
                $post->user->notify(new NewReactionOnPost(auth()->user(), $post));
            }
        }

        $reactionCount = Reaction::query()
            ->where('reactionable_id', $post->id)
            ->where('reactionable_type', Post::class)
            ->count();

        return response()->json([
            'reactions_count' => $reactionCount,
            'current_user_has_reaction' => $hasReaction,
        ]);
    }
}
