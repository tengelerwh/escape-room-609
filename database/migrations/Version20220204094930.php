<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204094930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('user');
        $table->addColumn('uuid', Types::GUID)
              ->setNotnull(true);
        $table->addColumn('createdAt', Types::DATETIMETZ_IMMUTABLE);
        $table->addColumn('login', Types::STRING)
              ->setLength(255)
              ->setNotnull(true);
        $table->addColumn('password', Types::STRING)
              ->setLength(150)
              ->setNotnull(true);

        $table->setPrimaryKey(['uuid']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('user');
    }
}
