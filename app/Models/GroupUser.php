<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupUser extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'created_by',
        'status',
        'user_id',
        'token_date_of_use',
        'token_expiration_date',
        'group_id',
        'role',
        'token',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
