<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use App\DomainModel\User\User;

class AuthenticationService implements AuthenticationServiceInterface
{
    private ?GameClient $currentClient;
    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function login(string $email, string $password): ?GameClient
    {
        //@todo call apiClient to handle login
        $user = User::create($email, $password, 'User name');
        // if already logged in, generate a new access token and refresh token
        $client = GameClient::new([]);
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

    public function persistClient(GameClient $client): void
    {
       $this->clientRepository->save($client);
    }

    public function isValidClient(GameClient $client): bool
    {
        // @todo implement validation
        return true;
    }

    public function refreshClient(RefreshToken $refreshToken): ?GameClient
    {
        $client = $this->clientRepository->findByRefreshToken($refreshToken);

        if (null === $client) {
            return null;
        }

        $client->setAccessToken(ClientAccessToken::create());
        $client->setRefreshToken(RefreshToken::create());
        $this->persistClient($client);

        $this->currentClient = $client;
        return $client;
    }

    public function getClientByAccessToken(?ClientAccessToken $accessToken, array $clientIdentification): ?GameClient
    {
        $client = $this->clientRepository->findByAccessToken($accessToken);

        // match client identification with stored version to make sure client is the same
        $clientData = sha1(json_encode($clientIdentification));

        return $client;
    }
}
