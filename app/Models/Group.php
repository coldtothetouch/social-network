<?php

namespace App\Models;

use App\Enums\GroupUserRole;
use App\Enums\GroupUserStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Group extends Model
{
    use SoftDeletes;
    use HasSlug;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'cover_path',
        'avatar_path',
        'private',
        'user_id',
        'deleted_by',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     *  Method returns currently authenticated GroupUser model
     */
    public function authGroupUser(): HasOne
    {
        return $this->hasOne(GroupUser::class)->where('user_id', auth()->id());
    }

    public function isAdmin(): bool
    {
        return $this->authGroupUser->role === GroupUserRole::ADMIN->value;
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivot('role', GroupUserRole::ADMIN->value);
    }

    public function pendingUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivot('status', GroupUserStatus::PENDING->value)
            ->orderBy('name');
    }

    public function approvedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_users')
            ->wherePivot('status', GroupUserStatus::APPROVED->value)
            ->orderBy('name');
    }
}
