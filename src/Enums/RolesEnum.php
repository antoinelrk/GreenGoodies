<?php
namespace App\Enums;

use App\Traits\BaseEnum;

enum RolesEnum: string
{
    use BaseEnum;

    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';
}
