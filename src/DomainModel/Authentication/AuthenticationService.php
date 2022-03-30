<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use App\DomainModel\User\User;

class AuthenticationService implements AuthenticationServiceInterface
{
    private ?ClientData $currentClient;

    public function login(string $email, string $password): ?ClientData
    {
        //@todo call apiClient to handle login
        $user = User::create($email, $password, 'User name');
        // if already logged in, generate a new access token and refresh token
        $client = ClientData::new([]);
        $client->setUser($user);

        $this->currentClient = $client;
        return $client;
    }

    public function isLoggedIn(): bool
    {
        if (null === $this->currentClient) {
            return false;
        }
        return true;
    }

    public function isValidAccessToken(?ClientAccessToken $accessToken): bool
    {
        if (null === $accessToken) {
            return false;
        }

        // @todo find access token and match with clientIdentification

        return true;
    }

    public function persistClient(ClientData $client): void
    {
       // @todo persist client
    }

    public function isValidClient(ClientData $clientData): bool
    {
        // @todo implement validation
        return true;
    }

    public function refreshClient(RefreshToken $refreshToken): ?ClientData
    {
        // load client by refresh token
        $client = ClientData::create([], ClientAccessToken::create()->toString(), RefreshToken::create()->toString());

        if (null === $client) {
            return null;
        }

        $user = User::create('wouter@test.nl', 'test', 'Wouter refreshed');
        // create new accessToken
        // create new refresh token

        $client->setUser($user);
        $this->persistClient($client);
        $this->currentClient = $client;
        return $client;
    }

    public function getClientByAccessToken(?ClientAccessToken $accessToken, array $clientIdentification): ?ClientData
    {
        // @todo load client

        $client = ClientData::new($clientIdentification);

        // match client identification with stored version to make sure client is the same
        $userData = sha1(json_encode($clientIdentification));

        return $client;
    }
}
