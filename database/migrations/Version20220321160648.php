<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321160648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added storage of client access';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('client');
        $table->addColumn('uuid', Types::GUID)
            ->setNotnull(true);
        $table->addColumn('accessToken', Types::GUID)
            ->setNotnull(true);
        $table->addColumn('refreshToken', Types::GUID)
            ->setNotnull(true);
        $table->addColumn('userToken', Types::GUID)
            ->setNotnull(false);
        $table->addColumn('createdAtUtc', Types::DATETIMETZ_IMMUTABLE);
        $table->addColumn('expiresAtUtc', Types::DATETIMETZ_IMMUTABLE);
        $table->addColumn('clientData', Types::STRING)
            ->setLength(1024)
            ->setNotnull(true);

        $table->setPrimaryKey(['uuid']);
        $table->addUniqueIndex(['accessToken'], 'idx_accessToken');
        $table->addUniqueIndex(['refreshToken'], 'idx_refreshToken');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('client');
    }
}
