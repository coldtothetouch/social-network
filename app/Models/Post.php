<?php

namespace App\Models;

use App\Http\Enums\ReactionEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'body',
        'user_id',
        'group_id',
        'deleted_by',
        'deleted_at',
        'preview',
        'preview_url',
        'is_pinned',
    ];

    protected $casts = [
        'preview' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOwnedByAuthUser(): bool
    {
        return $this->user->id === auth()->id();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class)->latest();
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->reactions()->where('type', ReactionEnum::LIKE);
    }

    public function scopeFeed(Builder $query, Collection $groups, Collection $followers): void
    {
        $followedGroupIds = $groups->pluck('id');
        $followedUserIds = $followers->pluck('id');

        $query->whereIn('group_id', $followedGroupIds)
            ->orWhere(function ($query) use ($followedUserIds) {
                $query->whereNull('group_id')->whereIn('user_id', $followedUserIds);
            });
    }
}
