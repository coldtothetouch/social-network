<?php

namespace Database\Factories;

use App\Enums\GroupUserRole;
use App\Enums\GroupUserStatus;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupUserFactory extends Factory
{
    protected $model = GroupUser::class;

    public function definition(): array
    {
        return [
            'token' => null,
            'token_date_of_use' => null,
            'token_expiration_date' => null,
            'role' => GroupUserRole::ADMIN,
            'status' => GroupUserStatus::APPROVED,
            'created_at' => Carbon::now(),

            'user_id' => DB::table('users')->pluck('id')->random(),
            'group_id' => DB::table('groups')->pluck('id')->random(),
            'created_by' => null,
        ];
    }
}
