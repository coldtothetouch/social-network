<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class UpdatePostRequest extends StorePostRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $post->user_id === Auth::id();
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'deleted_file_ids' => 'array',
            'deleted_file_ids.*' => 'integer'
        ]);
    }
}
