<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use App\DomainModel\Uuid;

class ClientAccessToken extends Uuid
{
    private Uuid $token;
}
