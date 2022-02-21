<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\DomainModel\DomainObject;
use App\DomainModel\EscapeRoom\EscapeRoom;
use App\DomainModel\EscapeRoom\EscapeRoomRepositoryInterface;
use App\DomainModel\ObjectId;
use App\DomainModel\Uuid;
use Doctrine\DBAL\Exception;

class EscapeRoomDatabaseRepository extends DatabaseRepository implements EscapeRoomRepositoryInterface
{
    private const TABLE = 'escape_room';

    /**
     * @param ObjectId $id
     * @return DomainObject|null
     * @throws Exception
     */
    public function findById(ObjectId $id): ?DomainObject
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE uuid = :uuid";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('uuid', (string) $id);

        $result = $statement->executeQuery();

        if (0 === $result->rowCount()) {
            return null;
        }

        return $this->mapToModel($result->fetchAssociative());
    }

    public function save(EscapeRoom $escapeRoom): void
    {
        if (null === $escapeRoom->getId()) {
            $this->insert($escapeRoom);
        } else {
            $this->update($escapeRoom);
        }
    }

    private function mapToModel($row): EscapeRoom
    {
        return EscapeRoom::create(
            $row['uuid'],
            $row['name'],
            $row['description']
        );
    }

    /**
     * @param EscapeRoom $escapeRoom
     * @throws Exception
     */
    private function insert(EscapeRoom $escapeRoom): void
    {
        $uuid = Uuid::create();
        $query = "INSERT INTO " . self::TABLE . " (uuid, name, description) VALUES (:uuid, :name, :description);";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('uuid', (string) $uuid->toString());
        $statement->bindValue('name', $escapeRoom->getName());
        $statement->bindValue('description', $escapeRoom->getDescription());

        $statement->executeQuery();
        $escapeRoom->setId($uuid);
    }

    /**
     * @param EscapeRoom $escapeRoom
     * @throws Exception
     */
    private function update(EscapeRoom $escapeRoom): void
    {
        $query = "UPDATE " . self::TABLE . " SET name = :name, description = :description WHERE uuid = :uuid";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('uuid', (string) $escapeRoom->getId());
        $statement->bindValue('name', $escapeRoom->getName());
        $statement->bindValue('description', $escapeRoom->getDescription());

        $statement->executeQuery();
    }
}
