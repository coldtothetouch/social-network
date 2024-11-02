<?php

namespace App\Http\Controllers;

use App\Enums\GroupUserRole;
use App\Enums\GroupUserStatus;
use App\Http\Requests\InviteUserRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Notifications\InvitationApproved;
use App\Notifications\InvitationInGroup;
use App\Notifications\NewGroupRequest;
use App\Notifications\RequestApproved;
use App\Notifications\RoleChanged;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class GroupController extends Controller
{
    public function show(Group $group)
    {
        return Inertia::render('Group/View', [
            'group' => GroupResource::make($group->load('authGroupUser')),
            'status' => session('status'),
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

    public function invite(Group $group, InviteUserRequest $request)
    {
        $request->validated();

        $user = $request->user;
        $groupUser = $request->groupUser;

        $groupUser?->delete();

        $token = str()->random(256);
        $hours = 24;

        GroupUser::query()->create([
            'status' => GroupUserStatus::PENDING->value,
            'token_expiration_date' => Carbon::now()->addHours($hours),
            'token' => $token,
            'group_id' => $group->id,
            'role' => GroupUserRole::SUBSCRIBER->value,
            'created_by' => auth()->id(),
            'user_id' => $user->id,
        ]);

        $user->notify(new InvitationInGroup($group, $token, $hours));

        return back()->with('status', 'User was invited');
    }

    public function acceptInvite(string $token)
    {
        $groupUser = GroupUser::query()->where('token', $token)->first();

        $errorTitle = '';

        if (!$groupUser) {
            $errorTitle = 'Token is not valid';
        } else if ($groupUser->token_date_of_use) {
            $errorTitle = 'Token is already used';
        } else if ($groupUser->token_expiration_date < Carbon::now()) {
            $errorTitle = 'Token is expired';
        }

        if ($errorTitle) {
            return Inertia::render('Error', compact('errorTitle'));
        }

        $groupUser->update([
            'status' => GroupUserStatus::APPROVED->value,
            'token_date_of_use' => Carbon::now(),
        ]);

        $admin = $groupUser->admin;
        $user = $groupUser->user;
        $group = $groupUser->group;

        $admin->notify(new InvitationApproved($user, $group));

        return redirect()->route('groups.show', $group)
            ->with('status', 'You have joined the group');
    }

    public function join(Group $group)
    {
        $status = GroupUserStatus::APPROVED->value;
        $message = "You have joined the group \"$group->name\"";

        if ($group->private) {
            $status = GroupUserStatus::PENDING->value;
            $message = "Your request has been sent";

            Notification::send($group->admins, new NewGroupRequest(auth()->user(), $group));
        }

        GroupUser::query()->create([
            'status' => $status,
            'role' => GroupUserRole::SUBSCRIBER->value,
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        return back()->with('status', $message);
    }

    public function approveUser(Group $group, Request $request)
    {
        if (!$group->isAdmin()) {
            return response('Forbidden', 403);
        }

        $data = $request->validate([
            'user_id' => 'required',
            'action' => 'required|string'
        ]);

        $groupUser = GroupUser::query()
            ->where('user_id', $data['user_id'])
            ->where('group_id', $group->id)
            ->where('status', GroupUserStatus::PENDING->value)
            ->first();

        if ($groupUser) {
            $approved = false;
            if ($data['action'] === 'approve') {
                $approved = true;
                $groupUser->status = GroupUserStatus::APPROVED->value;
            } else {
                $groupUser->status = GroupUserStatus::REJECTED->value;
            }

            $groupUser->save();
            $user = $groupUser->user;

            $user->notify(new RequestApproved($groupUser->group, $user, $approved));

            back()->with('status', "User $user->name was " . ($approved ? 'approved' : 'rejected'));
        }

        return back();
    }

    public function changeRole(Group $group, Request $request)
    {
        if (!$group->isAdmin()) {
            return response('Forbidden', 403);
        }

        $data = $request->validate([
            'user_id' => 'required',
            'role' => ['required', 'string', Rule::enum(GroupUserRole::class)],
        ]);

        if ($group->isOwner($data['user_id'])) {
            return response('Forbidden', 403);
        }

        $groupUser = GroupUser::query()
            ->where('user_id', $data['user_id'])
            ->where('group_id', $group->id)
            ->first();

        if ($groupUser) {
            $groupUser->update(['role' => $data['role']]);
            $groupUser->user->notify(new RoleChanged($group, $data['role']));
        }

        return back();
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update($request->validated());
        return back()->with('status', 'Group was updated');
    }

    public function destroy(Group $group)
    {
        //
    }
}
