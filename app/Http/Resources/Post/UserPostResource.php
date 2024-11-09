<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\PostAttachmentResource;
use App\Http\Resources\User\FeedPostUserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserPostResource extends PostResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'preview' => $this->preview,
            'preview_url' => $this->preview_url,
            'is_pinned' => $this->is_pinned,
            'group' => null,
            'created_at' => (new Carbon($this->created_at))->diffForHumans(),
            'updated_at' => (new Carbon($this->updated_at))->diffForHumans(),
            'user' => FeedPostUserResource::make($this->user),
            'attachments' => PostAttachmentResource::collection($this->attachments),
            'reactions_count' => $this->reactions->count(),
            'comments_count' => $this->comments->count(),
            'current_user_has_reaction' => $this->reactions->contains(function ($reaction) {
                return $reaction->user_id === auth()->id();
            }),
            'comments' => self::convertCommentsIntoTree($this->comments),
        ];
    }
}
