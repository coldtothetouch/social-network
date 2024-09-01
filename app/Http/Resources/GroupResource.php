<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'private' => $this->private,
            'role' => $this->authGroupUser?->role,
            'status' => $this->authGroupUser?->status,

            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => Str::words($this->description, 10),

            'cover_path' => $this->cover_path ? Storage::url($this->cover_path) : '/img/default_cover.jpg',
            'avatar_path' => $this->avatar_path ? Storage::url($this->avatar_path) : '/img/default_avatar.webp',

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //'deleted_at' => $this->deleted_at,
            //'deleted_by' => UserResource::make($this->deleted_by),
        ];
    }
}
