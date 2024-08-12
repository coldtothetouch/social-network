<?php

namespace App\Enums;

enum GroupUserStatus: string
{
    case APPROVED = 'approved';
    case PENDING = 'pending';
}
