<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

class AuthenticationService implements AuthenticationServiceInterface
{
    private ?ClientAccessToken $clientAccessToken = null;

    public function login(string $email, string $password): ?ClientAccessToken
    {
        //@todo call apiClient to handle login
        // if already logged in, generate a new access token
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

    public function hasValidAccessToken(array $clientIdentification, ?ClientAccessToken $accessToken): bool
    {
        if (null === $accessToken) {
            return false;
        }

        // @todo find access token and match with clientIdentification
        $userData = sha1(json_encode($clientIdentification));

        return true;
    }

    public function persistClient(array $clientIdentification, ClientAccessToken $accessToken): void
    {
        $userData = sha1(json_encode($clientIdentification));
    }
}
