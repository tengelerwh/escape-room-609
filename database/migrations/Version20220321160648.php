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
        $table->addColumn('token', Types::GUID)
            ->setNotnull(true);
        $table->addColumn('createdAt', Types::DATETIMETZ_IMMUTABLE);
        $table->addColumn('expiresAt', Types::DATETIMETZ_IMMUTABLE);
        $table->addColumn('user', Types::STRING)
            ->setLength(1024)
            ->setNotnull(true);

        $table->setPrimaryKey(['token']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('client');
    }
}
