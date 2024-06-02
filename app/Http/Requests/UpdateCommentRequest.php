<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $comment = $this->route('comment');

        return $comment->user_id === Auth::id();
    }

    public function rules(): array
    {
        return [
            'body' => 'required'
        ];
    }

    protected function passedValidation()
    {
        $this->body = nl2br($this->body);
    }
}
