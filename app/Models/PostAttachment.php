<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'post_id',
        'mime',
        'size',
        'name',
        'path',
        'crated_by',
    ];
}
