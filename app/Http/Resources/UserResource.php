<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $followers = $this->followers;

        return [
            'id' => $this->id,

            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,

            'cover_path' => $this->cover_path ? Storage::url($this->cover_path) : '/img/default_cover.jpg',
            'avatar_path' => $this->avatar_path ? Storage::url($this->avatar_path) : '/img/default_avatar.webp',

            'followers_count' => $followers->count(),
            'is_followed_by_auth_user' => $followers->contains(function ($item) {
                return $item->id === auth()->id();
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'email_verified_at' => $this->email_verified_at
        ];
    }
}
