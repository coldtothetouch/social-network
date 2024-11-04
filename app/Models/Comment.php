<?php

namespace App\Models;

use App\Http\Enums\ReactionEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;

    public array $childComments = [];

    public int $numOfComments = 0;

    protected $fillable = [
        'body',
        'user_id',
        'post_id',
        'parent_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }

    public function likes(): MorphMany
    {
        return $this->reactions()->where('type', ReactionEnum::LIKE);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function isOwnedByAuthUser(): bool
    {
        return $this->user_id === auth()->id();
    }
}
