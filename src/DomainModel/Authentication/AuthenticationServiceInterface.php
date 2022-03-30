<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use Symfony\Component\HttpFoundation\Request;

interface AuthenticationServiceInterface
{
    public function isLoggedIn(): bool;
    public function login(string $email, string $password): ?CLientData;
    public function isValidClient(ClientData $clientData): bool;
    public function getClientByAccessToken(?ClientAccessToken $accessToken, array $clientIdentification): ?ClientData;
    public function isValidAccessToken(?ClientAccessToken $accessToken): bool;
    public function persistClient(ClientData $client): void;
    public function refreshClient(RefreshToken $refreshToken): ?ClientData;
}
