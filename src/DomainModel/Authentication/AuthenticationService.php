<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use Symfony\Component\HttpFoundation\Request;

class AuthenticationService implements AuthenticationServiceInterface
{

    public function login(string $email, string $password): ?ClientAccessToken
    {
        //@todo call apiClient to handle login
        return null;
    }

    public function isLoggedIn(?ClientAccessToken $clientAccessToken): bool
    {
        if (null === $clientAccessToken) {
            return false;
        }
        return true;
    }

    public function getClientAccessTokenFromRequest(Request $request): ?ClientAccessToken
    {
        if (false === $request->headers->has('X-ACCESS-TOKEN')) {
            return null;
        }
        return ClientAccessToken::fromString($request->headers->get('X-ACCESS-TOKEN'));
    }
}
