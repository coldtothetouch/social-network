<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user_id' => $this->user_id,
            'role' => $this->authGroupUser?->role,
            'status' => $this->authGroupUser?->status,

            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
