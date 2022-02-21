<?php

declare(strict_types=1);

namespace App\DomainModel;

Interface DomainObject
{
    public function getId(): ?ObjectId;
}
