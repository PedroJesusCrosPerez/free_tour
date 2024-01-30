<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129175915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C4207988823A92');
        $this->addSql('DROP INDEX UNIQ_2C4207988823A92 ON route');
        $this->addSql('ALTER TABLE route DROP locality_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route ADD locality_id INT NOT NULL');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C4207988823A92 FOREIGN KEY (locality_id) REFERENCES locality (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C4207988823A92 ON route (locality_id)');
    }
}
