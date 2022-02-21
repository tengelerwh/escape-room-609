<?php

declare(strict_types=1);

namespace App\DomainModel;

interface RepositoryInterface
{
    public function findById(ObjectId $id): ?DomainObject;
}
