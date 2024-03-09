<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'attachments' => 'array|max:10',
            'attachments.*' => [
                'file',
                File::types([
                    'jpg', 'jpeg', 'png', 'gif', 'webp',
                    'mp3', 'wav', 'mp4',
                    'docx', 'doc', 'xlsx', 'xls', 'pdf', 'csv',
                    'zip', '7z', 'rar',
                ])->max(500 * 1024 * 1024)
            ],
            'body' => 'nullable|string|'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }
}
