<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

abstract class PostResource extends JsonResource
{
    /** @var Collection $comments */
    protected static function convertCommentsIntoTree(Collection $comments, $parentId = null): array
    {
        $commentTree = [];

        foreach ($comments as $comment) {
            if ($comment->parent_id === $parentId) {
                $children = self::convertCommentsIntoTree($comments, $comment->id);

                $comment->childComments = $children;
                $comment->numOfComments = collect($children)->sum('numOfComments') + count($children);

                $commentTree[] = CommentResource::make($comment);
            }
        }

        return $commentTree;
    }
}
