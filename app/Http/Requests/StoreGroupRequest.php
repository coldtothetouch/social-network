<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'private' => 'boolean|nullable',
            'name' => 'string|required|max:255',
            'description' => 'string|nullable|max:255',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'description' => nl2br($this->description),
        ]);
    }
}
