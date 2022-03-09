<?php

declare(strict_types=1);

namespace App\DomainModel;

interface ApiClient
{
    public function getTimeLeft(Uuid $gameId): array;
}
