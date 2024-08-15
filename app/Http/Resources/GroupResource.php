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
            'role' => $this->pivot?->role,
            'status' => $this->pivot?->status,

            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => Str::words($this->description, 10),

            'cover_url' => Storage::url($this->cover_path),
            'avatar_url' => Storage::url($this->avatar_path),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //'deleted_at' => $this->deleted_at,
            //'deleted_by' => UserResource::make($this->deleted_by),
        ];
    }
}
