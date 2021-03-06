<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use Symfony\Component\HttpFoundation\Request;

interface AuthenticationServiceInterface
{
    public function isLoggedIn(): bool;
    public function login(string $email, string $password, array $clientIdentification): ?GameClient;
    public function getClientByAccessToken(?ClientAccessToken $accessToken, array $clientIdentification): ?GameClient;
    public function isValidAccessToken(?ClientAccessToken $accessToken): bool;
    public function persistClient(GameClient $client): void;
    public function refreshClient(RefreshToken $refreshToken, array $clientIdentification): ?GameClient;
}
