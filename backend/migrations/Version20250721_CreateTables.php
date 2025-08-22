<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721_CreateTables extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create reservation table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE space (
            id INT AUTO_INCREMENT NOT NULL,
            uuid CHAR(36) NOT NULL,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            UNIQUE INDEX UNIQ_SPACE_UUID (uuid),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');



        $this->addSql('CREATE TABLE reservation (
                id INT AUTO_INCREMENT NOT NULL,
                uuid CHAR(36) NOT NULL,
                space_id INT NOT NULL,
                date DATE NOT NULL,
                hour INT NOT NULL,
                status VARCHAR(36) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                UNIQUE INDEX UNIQ_RESERVATION_UUID (uuid),
                UNIQUE INDEX uniq_reservation (space_id, date, hour),
                INDEX IDX_RESERVATION_SPACE (space_id),
                PRIMARY KEY(id),
                CONSTRAINT FK_RESERVATION_SPACE FOREIGN KEY (space_id) REFERENCES space (id) ON DELETE CASCADE
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE space');
        $this->addSql('DROP TABLE reservation');
    }
}
