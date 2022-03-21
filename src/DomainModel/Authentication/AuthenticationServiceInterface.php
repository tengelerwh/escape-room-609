<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use Symfony\Component\HttpFoundation\Request;

interface AuthenticationServiceInterface
{
    public function isLoggedIn(): bool;
    public function login(string $email, string $password): ?ClientAccessToken;
    public function hasValidAccessToken(array $clientIdentification, ?ClientAccessToken $accessToken): bool;
    public function persistClient(array $clientIdentification, ClientAccessToken $accessToken): void;
}
