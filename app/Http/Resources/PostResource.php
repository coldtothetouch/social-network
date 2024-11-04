<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $comments = $this->comments;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => (new Carbon($this->created_at))->diffForHumans(),
            'updated_at' => (new Carbon($this->updated_at))->diffForHumans(),
            'user' => UserResource::make($this->user),
            'group' => GroupResource::make($this->group),
            'attachments' => PostAttachmentResource::collection($this->attachments),
            'reactions_count' => $this->reactions_count,
            'comments_count' => count($comments),
            'current_user_has_reaction' => $this->reactions->count() > 0,
            'comments' => self::convertCommentsIntoTree($comments),
        ];
    }

    /** @var Collection $comments */
    private static function convertCommentsIntoTree(Collection $comments, $parentId = null): array
    {
        $commentTree = [];

        foreach ($comments as $comment) {
            if ($comment->parent_id === $parentId) {
                $children = self::convertCommentsIntoTree($comments, $comment->id);

                $comment->childComments = $children;
                $comment->numOfComments = collect($children)->sum('numOfComments') + count($children);

                $commentTree[] = new CommentResource($comment);
            }
        }

        return $commentTree;
    }
}
