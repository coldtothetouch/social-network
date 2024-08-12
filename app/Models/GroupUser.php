<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
