<?php

declare(strict_types=1);

namespace App\DomainModel;

use JsonSerializable;
use Symfony\Component\Uid\Uuid as BaseUuid;

class Uuid implements ObjectId, JsonSerializable
{
    private BaseUuid $uuid;

    private function __construct(string $uuid)
    {
        $this->uuid = BaseUuid::fromString($uuid);
    }

    public static function fromString(string $id): static
    {
        return new static($id);
    }

    public static function create(): static
    {
        return new static((string) BaseUuid::v4());
    }

    public function toString(): string
    {
        return (string) $this->uuid;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }
}
