<?php

namespace App\Http\Controllers;

use App\Enums\GroupUserRole;
use App\Enums\GroupUserStatus;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class GroupController extends Controller
{
    public function show(Group $group)
    {
        return Inertia::render('Group/View', [
            'group' => GroupResource::make($group->load('authGroupUser')),
            'status' => session('status')
        ]);
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
    public function updateImage(Request $request, Group $group)
    {
        $group->load('authGroupUser');

        if ($group->authGroupUser->role !== 'admin') {
            return response(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'cover' => 'nullable|image',
            'avatar' => 'nullable|image',
        ]);

        /** @var UploadedFile $avatar */
        $avatar = $data['avatar'] ?? null;

        /** @var UploadedFile $cover */
        $cover = $data['cover'] ?? null;

        $status = '';

        if ($cover) {
            if ($group->cover_path) {
                Storage::disk('public')->delete($group->cover_path);
            }
            $path = $cover->store("group-$group->id", 'public');
            $group->update(['cover_path' => $path]);
            $status = 'Cover image updated';
        }

        if ($avatar) {
            if ($group->avatar_path) {
                Storage::disk('public')->delete($group->avatar_path);
            }
            $path = $avatar->store("user-$group->id", 'public');
            $group->update(['avatar_path' => $path]);
            $status = 'Group avatar updated';
        }

        return back()->with('status', $status);
    }

    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
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
