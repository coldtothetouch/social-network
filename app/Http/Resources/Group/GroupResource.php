<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\User\GroupUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'private' => $this->private,
            'role' => $this->authgroupUser?->role,
            'status' => $this->authgroupUser?->status,

            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,

            'cover_path' => $this->cover_path ? Storage::url($this->cover_path) : '/img/default_cover.jpg',
            'avatar_path' => $this->avatar_path ? Storage::url($this->avatar_path) : '/img/default_avatar.webp',

            'follower_count' => $this->approvedUsers->count() ?? 0,

            'users' => !!$this->private && !$this->authgroupUser?->isApproved() ? null : GroupUserResource::collection($this->approvedUsers),
            'pending_users' => GroupUserResource::collection($this->pendingUsers),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
