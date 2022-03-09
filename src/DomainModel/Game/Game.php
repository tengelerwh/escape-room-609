<?php
declare(strict_types = 1);

namespace App\DomainModel\Game;

use App\DomainModel\ObjectId;
use App\DomainModel\Uuid;

class Game
{
    private ?Uuid $id;
    private ?Uuid $Userid;

    public static function create(
        ?string $uuid,
        string $userId,
        ?string $status,
    ): self
    {
        $id = null;
        if (null !== $uuid) {
            $id = Uuid::fromString($uuid);
        }
        return new self(
            $id,
            Uuid::fromString($userId)
        );
    }

    public static function new(string $userId): self
    {
        return new self(
            null,
            Uuid::fromString($userId)
        );
    }

    private function __construct(
        ?Uuid $uuid,
        Uuid $userId,
    ) {
        $this->id = $uuid;
        $this->userId = $userId;
    }

    public function getId(): ?ObjectId
    {
        return $this->id;
    }

    public function setId(Uuid $uuid): void
    {
        $this->id = $uuid;
    }
}
