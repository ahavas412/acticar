<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203214223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69DF5AA79D0 ON car (license_plate)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_773DE69DF5AA79D0 ON car');
        $this->addSql('ALTER TABLE car CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE state state VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE license_plate license_plate VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
