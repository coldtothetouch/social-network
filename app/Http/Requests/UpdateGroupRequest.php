<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Group $group */
        $group = $this->route('group');

        return $group->authUserIsAdmin();
    }

    public function rules(): array
    {
        return [
            'private' => 'bool|nullable',
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
