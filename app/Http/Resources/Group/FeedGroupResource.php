<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\GroupUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FeedGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'avatar_path' => $this->avatar_path ? Storage::url($this->avatar_path) : '/img/default_avatar.webp',
        ];
    }
}
