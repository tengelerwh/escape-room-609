<?php

declare(strict_types=1);

namespace App\DomainModel\User;

use App\DomainModel\Uuid;

class UserToken extends Uuid
{
    private Uuid $token;
}
