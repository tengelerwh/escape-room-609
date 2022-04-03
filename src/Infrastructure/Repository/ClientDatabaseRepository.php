<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\DomainModel\Authentication\ClientAccessToken;
use App\DomainModel\Authentication\GameClient;
use App\DomainModel\Authentication\ClientRepositoryInterface;
use App\DomainModel\Authentication\AuthenticationServiceInterface;
use App\DomainModel\Authentication\RefreshToken;
use App\DomainModel\DomainObject;
use App\DomainModel\Game\Game;
use App\DomainModel\Game\GameRepositoryInterface;
use App\DomainModel\ObjectId;
use App\DomainModel\Uuid;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Exception;

class ClientDatabaseRepository extends DatabaseRepository implements ClientRepositoryInterface
{
    private const TABLE = 'client';

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

    /**
     * @param ClientAccessToken $accessToken
     * @return DomainObject|null
     * @throws Exception
     */
    public function findByAccessToken(ClientAccessToken $accessToken): ?GameClient
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE accessToken = :accessToken";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('accessToken', (string) $accessToken);

        $result = $statement->executeQuery();

        if (0 === $result->rowCount()) {
            return null;
        }

        return $this->mapToModel($result->fetchAssociative());
    }

    /**
     * @param RefreshToken $refreshToken
     * @return DomainObject|null
     * @throws Exception
     */
    public function findByRefreshToken(RefreshToken $refreshToken): ?GameClient
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE refreshToken = :refreshToken";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('refreshToken', (string) $refreshToken);

        $result = $statement->executeQuery();

        if (0 === $result->rowCount()) {
            return null;
        }

        return $this->mapToModel($result->fetchAssociative());
    }

    public function save(GameClient $client): void
    {
        if (null === $client->getId()) {
            $this->insert($client);
        } else {
            $this->update($client);
        }
    }

    private function mapToModel($row): GameClient
    {
        $createdAtUtc = DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT, $row['createdAtUtc'], new DateTimeZone('UTC'));
        $expiresAtUtc = DateTime::createFromFormat(self::DATETIME_FORMAT, $row['expiresAtUtc'],  new DateTimeZone('UTC'));

        $clientData = json_decode($row['clientData'], true);
        return GameClient::create(
            $row['uuid'],
            $row['accessToken'],
            $row['refreshToken'],
            $row['userToken'],
            $createdAtUtc,
            $expiresAtUtc,
            $clientData,
        );
    }

    /**
     * @param GameClient $clientData
     * @throws Exception
     */
    private function insert(GameClient $client): void
    {
        $uuid = Uuid::create();
        $query = "INSERT INTO " .
            self::TABLE .
            " (uuid, accessToken, refreshToken, userToken, createdAtUtc, expiresAtUtc, clientData) " .
            "VALUES (:uuid, :accessToken, :refreshToken, :userToken, :createdAtUtc, :expiresAtUtc, :clientData);";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('uuid', (string) $uuid);
        $statement->bindValue('accessToken', (string) $client->getAccessToken());
        $statement->bindValue('refreshToken', (string) $client->getRefreshToken());
        $statement->bindValue('userToken', $client->getUserToken() ? (string) $client->getRefreshToken() : null);
        $statement->bindValue('createdAtUtc', $client->getCreatedAtUtc()->format(self::DATETIME_FORMAT));
        $statement->bindValue('expiresAtUtc', $client->getExpiresAtUtc()->format(self::DATETIME_FORMAT));
        $statement->bindValue('clientData', json_encode($client->getClientIdentification()));

        $statement->executeQuery();
        $client->setId($uuid);
    }

    /**
     * @param GameClient $client
     * @throws Exception
     */
    private function update(GameClient $client): void
    {
        $query = "UPDATE " . self::TABLE . " SET " .
            "accessToken = :accessToken " .
            ",refreshToken = :refreshToken " .
            ",userToken = :userToken " .
            ",createdAtUtc = :createdAtUtc " .
            ",expiresAtUtc = :expiresAtUtc " .
            ",clientData = :clientData " .
            "WHERE uuid = :uuid";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue('uuid', (string) $client->getId());
        $statement->bindValue('accessToken', (string) $client->getAccessToken());
        $statement->bindValue('refreshToken', (string) $client->getRefreshToken());
        $statement->bindValue('userToken', $client->getUserToken() ? (string) $client->getRefreshToken() : null);
        $statement->bindValue('createdAtUtc', $client->getCreatedAtUtc()->format(self::DATETIME_FORMAT));
        $statement->bindValue('expiresAtUtc', $client->getExpiresAtUtc()->format(self::DATETIME_FORMAT));
        $statement->bindValue('clientData', json_encode($client->getClientIdentification()));

        $statement->executeQuery();
    }
}
