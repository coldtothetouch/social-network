<?php

namespace App\Models;

use App\Enums\GroupUserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupUser extends Model
{
    use HasFactory;

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

    public function isApproved(): bool
    {
        return $this->status === GroupUserStatus::APPROVED->value;
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
