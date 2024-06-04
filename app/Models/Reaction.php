<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'type',
        'user_id',
        'reactionable_id',
        'reactionable_type',
    ];

    public function reactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
