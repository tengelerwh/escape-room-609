<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\DomainModel\RepositoryInterface;
use Doctrine\DBAL\Connection;

abstract class DatabaseRepository implements RepositoryInterface
{
    public const DATETIME_FORMAT = 'Y-m-d H:i:s';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
