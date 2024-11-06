<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewFollower;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index(Request $request, User $user)
    {
        $posts = Post::query()
            ->with('user')
            ->where('user_id', $user->id)
            ->where('group_id', null)
            ->latest()
            ->paginate(10);

        if ($request->wantsJson()) {
            return PostResource::collection($posts);
        }

        return Inertia::render('Profile/View', [
            'user' => UserResource::make($user),
            'posts' => PostResource::collection($posts),
            'followers' => UserResource::collection($user->followers),
            'followings' => UserResource::collection($user->followings),
            'status' => session('status'),
        ]);
    }

    public function updateImage(Request $request)
    {
        $data = $request->validate([
            'cover' => 'nullable|image',
            'avatar' => 'nullable|image',
        ]);

        /** @var UploadedFile $avatar */
        $avatar = $data['avatar'] ?? null;
        /** @var UploadedFile $cover */
        $cover = $data['cover'] ?? null;

        $user = auth()->user();

        $status = '';

        if ($cover) {
            if ($user->cover_path) {
                Storage::disk('public')->delete($user->cover_path);
            }
            $path = $cover->store("user-$user->id", 'public');
            $user->update(['cover_path' => $path]);
            $status = 'Cover image updated';
        }

        if ($avatar) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $avatar->store("user-$user->id", 'public');
            $user->update(['avatar_path' => $path]);
            $status = 'Profile avatar updated';
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

    public function followUser(User $user)
    {
        $follower = Follower::query()
            ->where('follower_id', auth()->id())
            ->where('user_id', $user->id)
            ->first();

        $message = 'You followed this user.';

        if ($follower) {
            $follower->delete();
            $message = 'You unfollowed this user.';
        } else {
            Follower::query()->create([
                'follower_id' => auth()->id(),
                'user_id' => $user->id,
            ]);

            $user->notify(new NewFollower(auth()->user()));
        }

        return back()->withStatus($message);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile.index', $request->user())->with('success', 'User information was updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
