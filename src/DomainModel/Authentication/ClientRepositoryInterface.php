<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use App\DomainModel\RepositoryInterface;

interface ClientRepositoryInterface extends RepositoryInterface
{
    public function findByAccessToken(ClientAccessToken $accessToken): ?GameClient;
    public function findByRefreshToken(RefreshToken $refreshToken): ?GameClient;
}
