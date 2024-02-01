<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201083442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, image LONGBLOB NOT NULL, observation LONGTEXT NOT NULL, money DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_C42F778415ED8D43 (tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778415ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('DROP TABLE name');
        $this->addSql('ALTER TABLE item CHANGE description description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE name (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778415ED8D43');
        $this->addSql('DROP TABLE report');
        $this->addSql('ALTER TABLE item CHANGE description description VARCHAR(255) NOT NULL');
    }
}
