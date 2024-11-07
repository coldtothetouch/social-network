<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostAttachment extends Model
{
    const UPDATED_AT = null;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($attachment) {
            Storage::disk('public')->delete($attachment->path);
        });
    }

    protected $fillable = [
        'post_id',
        'mime',
        'size',
        'name',
        'path',
        'created_by',
    ];
}
