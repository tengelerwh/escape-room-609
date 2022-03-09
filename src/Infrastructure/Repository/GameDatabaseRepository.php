<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\DomainModel\DomainObject;
use App\DomainModel\Game\Game;
use App\DomainModel\Game\GameRepositoryInterface;
use App\DomainModel\ObjectId;
use App\DomainModel\Uuid;
use Doctrine\DBAL\Exception;

class GameDatabaseRepository extends DatabaseRepository implements GameRepositoryInterface
{
    private const TABLE = 'game';

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

    public function save(Game $game): void
    {
        if (null === $game->getId()) {
            $this->insert($game);
        } else {
            $this->update($game);
        }
    }

    private function mapToModel($row): Game
    {
        return Game::create(
            $row['uuid'],
            $row['name'],
            $row['description']
        );
    }

    /**
     * @param Game $game
     * @throws Exception
     */
    private function insert(Game $game): void
    {
        $uuid = Uuid::create();
        $query = "INSERT INTO " . self::TABLE . " (uuid, name, description) VALUES (:uuid, :name, :description);";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('uuid', (string) $uuid->toString());
        $statement->bindValue('name', $game->getName());
        $statement->bindValue('description', $game->getDescription());

        $statement->executeQuery();
        $game->setId($uuid);
    }

    /**
     * @param Game $game
     * @throws Exception
     */
    private function update(Game $game): void
    {
        $query = "UPDATE " . self::TABLE . " SET name = :name, description = :description WHERE uuid = :uuid";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('uuid', (string) $game->getId());
        $statement->bindValue('name', $game->getName());
        $statement->bindValue('description', $game->getDescription());

        $statement->executeQuery();
    }
}
