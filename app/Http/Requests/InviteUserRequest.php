<?php

namespace App\Http\Requests;

use App\Enums\GroupUserStatus;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class InviteUserRequest extends FormRequest
{
    public ?User $user = null;
    public Group $group;
    public ?GroupUser $groupUser = null;

    public function authorize(): bool
    {
        $this->group = $this->route('group');
        return $this->group->authUserIsAdmin();
    }

    public function rules(): array
    {
        return [
            'email' => ['required', function ($attribute, $value, Closure $fail) {
                $this->user = User::query()->where('email', $value)
                    ->orWhere('username', $value)
                    ->first();

                if (!$this->user) {
                    $fail('User does not exist');
                }

                $this->groupUser = GroupUser::query()
                    ->where('user_id', $this->user->id)
                    ->where('group_id', $this->group->id)
                    ->first();

                if ($this->groupUser && $this->groupUser->status === GroupUserStatus::APPROVED->value) {
                    $fail('User is already in group');
                }
            }]
        ];
    }
}
