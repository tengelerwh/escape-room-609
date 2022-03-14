<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use Symfony\Component\HttpFoundation\Request;

class AuthenticationService implements AuthenticationServiceInterface
{
    private ?ClientAccessToken $clientAccessToken = null;

    public function login(string $email, string $password): ?ClientAccessToken
    {
        //@todo call apiClient to handle login
        $this->clientAccessToken = ClientAccessToken::create();
        return $this->clientAccessToken;
    }

    public function isLoggedIn(): bool
    {
        if (null === $this->clientAccessToken) {
            return false;
        }
        return true;
    }

    public function getClientAccessTokenFromRequest(Request $request): ?ClientAccessToken
    {
        if (false === $request->headers->has('X-ACCESS-TOKEN')) {
            return null;
        }
        $this->clientAccessToken = ClientAccessToken::fromString($request->headers->get('X-ACCESS-TOKEN'));
        return $this->clientAccessToken;
    }

    public function getStoredClientAccessToken(): ?ClientAccessToken
    {
        return $this->clientAccessToken;
    }
}
