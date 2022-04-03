<?php

declare(strict_types=1);

namespace App\DomainModel\Authentication;

use App\DomainModel\DomainObject;
use App\DomainModel\ObjectId;
use App\DomainModel\User\User;
use App\DomainModel\User\UserToken;
use App\DomainModel\Uuid;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use JsonSerializable;

class GameClient implements DomainObject, JsonSerializable
{
    private ?Uuid $id;

    private ClientAccessToken $accessToken;

    private RefreshToken $refreshToken;

    private ?UserToken $userToken;

    private ?User $user;

    private DateTimeImmutable $createdAtUtc;

    private DateTime $expiresAtUtc;

    private array $clientIdentification;

    public static function create(
        ?string $id,
        string $accessToken,
        string $refreshToken,
        ?string $user,
        DateTimeImmutable $createdAtUtc,
        DateTime $expiresAtUtc,
        array $clientIdentification
    ): static
    {
        $id = (null !== $id) ? Uuid::fromString($id) : null;
        $userToken = (null !== $user) ? UserToken::fromString($user) : null;

        return new static(
            $id,
            ClientAccessToken::fromString($accessToken),
            RefreshToken::fromString($refreshToken),
            $userToken,
            $createdAtUtc,
            $expiresAtUtc,
            $clientIdentification
        );
    }

    public static function new(array $clientIdentification): static
    {
        $createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $expiresAt = DateTime::createFromImmutable($createdAt);
        $expiresAt->add(new DateInterval('PT1H'));

        return new static(
            null,
            ClientAccessToken::create(),
            RefreshToken::create(),
            null,
            $createdAt,
            $expiresAt,
            $clientIdentification
        );
    }

    public function __construct(
        ?Uuid $id,
        ClientAccessToken $accessToken,
        RefreshToken $refreshToken,
        ?UserToken $userToken,
        DateTimeImmutable $createdAtUtc,
        DateTime $expiresAtUtc,
        array $clientIdentification,
    )
    {
        $this->id = $id;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->userToken = $userToken;
        $this->createdAtUtc = $createdAtUtc;
        $this->expiresAtUtc = $expiresAtUtc;
        $this->user = null;
        $this->clientIdentification = $clientIdentification;
    }

    public function setId(UuId $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?ObjectId
    {
        return $this->id;
    }

    public function setAccessToken(ClientAccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken(): ClientAccessToken
    {
        return $this->accessToken;
    }

    public function setRefreshToken(RefreshToken $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
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

    public function getUserToken(): ?UserToken
    {
        return $this->userToken;
    }

    public function getCreatedAtUtc(): DateTimeImmutable
    {
        return $this->createdAtUtc;
    }

    public function getExpiresAtUtc(): DateTime
    {
        return $this->expiresAtUtc;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (null !== $this->id) ? $this->id->toString() : null,
            'accessToken' => $this->accessToken->toString(),
            'refreshToken' => $this->refreshToken->toString(),
            'userToken' => (null !== $this->userToken) ? $this->userToken->toString() : null,
            'createdAt' => $this->createdAtUtc->format('Y-m-d H:i:s'),
            'expiresAt' => $this->expiresAtUtc->format('Y-m-d H:i:s'),
            'clientIdentification' => $this->clientIdentification,
            'user' => $this->getUser(),
        ];
    }

    public function toString(): string
    {
        return json_encode($this->jsonSerialize());
    }
}
