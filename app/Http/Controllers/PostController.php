<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function download(PostAttachment $attachment): BinaryFileResponse
    {
        // Both approaches are working

        return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);
        //Storage::download("app/public/$attachment->path", $attachment->name);
    }

    public function destroy(Post $post): Response|RedirectResponse
    {
        if ($post->user_id !== auth()->id()) {
            return response("You don't have permission to delete this post", 403);
        }

        $post->delete();
        return back();
    }
}
