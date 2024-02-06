<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'body' => $this->body,
            'created_at' => (new Carbon($this->created_at))->diffForHumans(),
            'updated_at' => (new Carbon($this->updated_at))->diffForHumans(),
            'user' => new UserResource($this->user),
            'group' => $this->group,
            'attachments' => $this->attachments,
        ];
    }
}
