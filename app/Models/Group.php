<?php

namespace App\Models;

use App\Enums\GroupUserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
