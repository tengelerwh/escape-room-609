<?php

declare(strict_types=1);

namespace App\DomainModel;

interface ObjectId
{
    public static function fromString(string $id): ObjectId;
    public function toString(): string;
}
