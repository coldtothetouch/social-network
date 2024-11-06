<?php

namespace App\Http\Resources;

use App\Enums\GroupUserStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $approvedUsers = User::query()
            ->select(['users.*', 'gu.role', 'gu.status'])
            ->join('group_users as gu', 'gu.user_id', 'users.id')
            ->where('gu.group_id', $this->id)
            ->where('gu.status', GroupUserStatus::APPROVED->value);

        $authGroupUser = $this->authGroupUser;

        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'private' => $this->private,
            'role' => $authGroupUser?->role,
            'status' => $authGroupUser?->status,

            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => Str::words(strip_tags($this->description), 10),

            'cover_path' => $this->cover_path ? Storage::url($this->cover_path) : '/img/default_cover.jpg',
            'avatar_path' => $this->avatar_path ? Storage::url($this->avatar_path) : '/img/default_avatar.webp',

            'follower_count' => $approvedUsers->count() ?? 0,

            'users' => !!$this->private && !$authGroupUser?->isApproved() ? null : GroupUserResource::collection($approvedUsers->get()),
            'pending_users' => GroupUserResource::collection($this->pendingUsers),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //'deleted_at' => $this->deleted_at,
            //'deleted_by' => UserResource::make($this->deleted_by),
        ];
    }
}
