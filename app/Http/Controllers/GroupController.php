<?php

namespace App\Http\Controllers;

use App\Enums\GroupUserRole;
use App\Enums\GroupUserStatus;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupUser;

class GroupController extends Controller
{
    public function index()
    {
        //
    }

    public function store(StoreGroupRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $group = Group::query()->create($data);

        $groupUserData = [
            'user_id' => auth()->id(),
            'created_by' => auth()->id(),
            'group_id' => $group['id'],
            'role' => GroupUserRole::ADMIN->value,
            'status' => GroupUserStatus::APPROVED->value,
        ];

        GroupUser::query()->create($groupUserData);

        $group = auth()->user()->groups()->where('groups.id', $group['id'])->first();

        return response(GroupResource::make($group), 201);
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        //
    }

    public function destroy(Group $group)
    {
        //
    }
}
