<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use Symfony\Component\HttpFoundation\Request;

interface AuthenticationServiceInterface
{
    public function getClientAccessTokenFromRequest(Request $request): ?ClientAccessToken;
    public function isLoggedIn(?ClientAccessToken $clientAccessToken): bool;
    public function login(string $email, string $password): ?ClientAccessToken;
}
