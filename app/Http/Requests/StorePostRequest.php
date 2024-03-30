<?php

namespace App\Http\Requests;

use App\Models\PostAttachment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use phpDocumentor\Reflection\Types\Integer;

class StorePostRequest extends FormRequest
{
    public static array $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'mp3', 'wav', 'mp4',
        'docx', 'doc', 'xlsx', 'xls', 'pdf', 'csv',
        'zip', '7z', 'rar',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'attachments' => [
                'array',
                'max:9',
                function ($attribute, $value, $fail) {
                    $totalSize = collect($value)->sum(fn(UploadedFile $file) => $file->getSize());

                    if ($totalSize > 1000 * 1024) {
                        $fail('The total size of all files must not exceed 1GB.');
                    }
                }
            ],
            'attachments.*' => [
                'file',
                File::types(self::$allowedExtensions)
            ],
            'body' => 'nullable|string'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    public function messages(): array
    {
        return [

            'attachments.*.file' => 'Each attachment must be a file',
            'attachments.*.mimes' => 'Invalid file type',
        ];
    }
}
