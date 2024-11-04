<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    public function store(Request $request, Post $post): CommentResource
    {
        $data = $request->validate([
            'comment' => 'required',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::query()->create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'body' => nl2br($data['comment']),
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return CommentResource::make($comment, 201);
    }

    public function update(UpdateCommentRequest $request, Comment $comment): CommentResource
    {
        $data = $request->validated();

        $comment->update($data);

        return CommentResource::make($comment);
    }

    public function destroy(Comment $comment): Response|RedirectResponse
    {
        if ($comment->isOwnedByAuthUser() || $comment->post->group?->authUserIsAdmin()) {
            $comment->delete();
            return back();
        }

        return response("You don't have permission to delete this comment", 403);
    }
}
