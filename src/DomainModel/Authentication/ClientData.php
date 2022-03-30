<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use App\DomainModel\User\User;
use JsonSerializable;

class ClientData implements JsonSerializable
{
    private ClientAccessToken $accessToken;

    private RefreshToken $refreshToken;

    private ?User $user;

    private array $clientIdentification;

    public static function create(array $clientIdentification, string $accessToken, string $refreshToken): static
    {
        return new static(
            $clientIdentification,
            ClientAccessToken::fromString($accessToken),
            RefreshToken::fromString($refreshToken)
        );
    }

    public static function new(array $clientIdentification): static
    {
        return new static(
            $clientIdentification,
            ClientAccessToken::create(),
            RefreshToken::create()
        );
    }

    public function __construct(array $clientIdentification, ClientAccessToken $accessToken, RefreshToken $refreshToken)
    {
        $this->clientIdentification = $clientIdentification;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->user = null;
    }

    public function getAccessToken(): ClientAccessToken
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): RefreshToken
    {
        return $this->refreshToken;
    }

    public function getClientIdentification(): array
    {
        return $this->clientIdentification;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function jsonSerialize(): array
    {
        return [
            'refreshToken' => $this->refreshToken->toString(),
            'accessToken' => $this->accessToken->toString(),
            'clientId' => $this->clientIdentification,
            'user' => $this->getUser(),
        ];
    }
}
