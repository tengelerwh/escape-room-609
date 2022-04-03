<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use App\DomainModel\User\User;
use Psr\Log\LoggerInterface;

class AuthenticationService implements AuthenticationServiceInterface
{
    private ?GameClient $currentClient;
    private ClientRepositoryInterface $clientRepository;
    private LoggerInterface $logger;

    public function __construct(
        ClientRepositoryInterface $clientRepository,
        LoggerInterface $logger
    ) {
        $this->clientRepository = $clientRepository;
        $this->logger = $logger;
    }

    public function login(string $email, string $password, array $clientIdentification): ?GameClient
    {
        //@todo call apiClient to handle login
        $user = User::create($email, $password, 'User name');
        // if already logged in, generate a new access token and refresh token
        $client = GameClient::new($clientIdentification);
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
        $this->logger->debug(sprintf('persistClient: %s', $client->toString()));

        $this->clientRepository->save($client);
    }

    public function refreshClient(RefreshToken $refreshToken, array $clientIdentification): ?GameClient
    {
        $this->logger->debug(sprintf('RefreshClient: token %s', $refreshToken));
        $client = $this->clientRepository->findByRefreshToken($refreshToken);

        if (null === $client) {
            return null;
        }

        $this->logger->debug(sprintf('Client retrieved : %s', $client->toString()));

        if (false === $this->matchClientIdentification($client, $clientIdentification)) {
            return null;
        }

        $client->setAccessToken(ClientAccessToken::create());
        $client->setRefreshToken(RefreshToken::create());
        $client->resetExpiration();
        $this->persistClient($client);

        $this->currentClient = $client;
        return $client;
    }

    public function getClientByAccessToken(?ClientAccessToken $accessToken, array $clientIdentification): ?GameClient
    {
        $client = $this->clientRepository->findByAccessToken($accessToken);

        if (false === $this->matchClientIdentification($client, $clientIdentification)) {
            return null;
        }

        return $client;
    }

    /**
     * All elements in the client identification must match
     *
     * @param GameClient $client
     * @param array $clientIdentification
     * @return bool
     */
    public function matchClientIdentification(GameClient $client, array $clientIdentification): bool
    {
        $orgData = $client->getClientIdentification();
        foreach ($orgData as $name => $item) {
            if (false === array_key_exists($name, $clientIdentification)) {
                return false;
            }
            if ($item !== $clientIdentification[$name]) {
                return false;
            }
        }
        return true;
    }
}
