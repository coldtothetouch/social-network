<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\User;
use App\Notifications\NewPost;
use DOMDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class PostController extends Controller
{
    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $allFilePaths = [];
        try {
            DB::beginTransaction();

            $post = Post::query()->create($data);

            /** @var UploadedFile[] $files */
            $files = $data['attachments'] ?? [];

            foreach ($files as $file) {
                $path = $file->storeAs(
                    "attachments/$post->id",
                    Str::random(32) . ".{$file->extension()}",
                    'public'
                );

                $allFilePaths[] = $path;

                PostAttachment::query()->create([
                    'post_id' => $post->id,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'created_by' => $request->user()->id,
                ]);
            }

            $group = $post->group;

            if ($group) {
                Notification::send(
                    $group->approvedUsers()->where('user_id', '!=', auth()->id())->get(),
                    new NewPost($post, $group)
                );
            } else {
                Notification::send(
                    User::query()->find($data['user_id'])->followers,
                    new NewPost($post)
                );
            }

            Db::commit();
        } catch (Throwable) {
            Storage::disk('public')->delete($allFilePaths);
            Db::rollBack();
        }

        return back();
    }

    public function fetchUrlPreview(Request $request)
    {
        $data = $request->validate(['url' => 'required|url']);
        $url = $data['url'];

        $content = file_get_contents($url);

        $dom = new DOMDocument();

        libxml_use_internal_errors(true);

        $dom->loadHTML($content);

        libxml_use_internal_errors(false);

        $ogTags = [];
        $metaTags = $dom->getElementsByTagName('meta');

        foreach ($metaTags as $metaTag) {
            $property = $metaTag->getAttribute('property');
            if (str_starts_with($property, 'og:')) {
                $ogTags[$property] = $metaTag->getAttribute('content');
            }
        }

        return $ogTags;
    }

    public function show(Post $post): \Inertia\Response
    {
        $post->loadCount(['reactions']);

        $post->load([
            'comments' => function ($query) {
                $query->withCount('likes');
            }
        ]);


        return Inertia::render('Post/Show', [
            'post' => PostResource::make($post),
        ]);
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        $allFilePaths = [];
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $post->update($data);

            $deletedFileIds = $data['deleted_file_ids'] ?? [];

            if ($deletedFileIds) {

                $attachments = PostAttachment::query()
                    ->where('post_id', $post->id)
                    ->whereIn('id', $deletedFileIds)
                    ->get();

                foreach ($attachments as $attachment) {
                    $attachment->delete();
                }
            }

            /** @var UploadedFile[] $files */
            $files = $data['attachments'];

            foreach ($files as $file) {
                $path = $file->storeAs("attachments/$post->id", Str::random(32) . '.jpg', 'public');
                $allFilePaths[] = $path;

                PostAttachment::query()->create([
                    'post_id' => $post->id,
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'created_by' => $request->user(),
                ]);
            }

            Db::commit();
        } catch (Throwable) {
            Storage::disk('public')->delete($allFilePaths);
            Db::rollBack();
        }

        return back();
    }

    public function generate(Request $request)
    {
        $prompt = $request->get('prompt');

        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Сгенерируй пост из социальной сети на основе этого промпта: $prompt"
                ],
            ],
        ]);

        return response(['content' => $result->choices[0]->message->content]);
    }

    public function pin(Post $post)
    {
        if ($post->group?->authUserIsAdmin()) {
            Post::query()
                ->where('group_id', $post->group->id)
                ->where('is_pinned', true)
                ->update(['is_pinned' => false]);

            $post->update(['is_pinned' => !$post->is_pinned]);

            return back()->with('status', $post->is_pinned ? 'Post pinned' : 'Post unpinned');
        } else if ($post->isOwnedByAuthUser() && is_null($post->group)) {
            Post::query()
                ->whereNull('group_id')
                ->where('user_id', auth()->id())->update(['is_pinned' => false]);

            $post->update(['is_pinned' => !$post->is_pinned]);

            return back()->with('status', $post->is_pinned ? 'Post pinned' : 'Post unpinned');
        }

        return response('Forbidden', 403);
    }

    public function download(PostAttachment $attachment): BinaryFileResponse
    {
        // Both approaches are working

        return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);
        //Storage::download("app/public/$attachment->path", $attachment->name);
    }

    public function destroy(Post $post): Response|RedirectResponse
    {
        if ($post->isOwnedByAuthUser() || $post->group?->authUserIsAdmin()) {
            $post->attachments->each(function ($attachment) {
                $attachment->delete();
            });
            $post->delete();
            return back();
        }

        return response("You don't have permission to delete this post", 403);
    }
}
