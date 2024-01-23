<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240123120358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, photo LONGBLOB NOT NULL, gps VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locality (id INT AUTO_INCREMENT NOT NULL, province_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E1D6B8E6E946114A (province_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE name (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE province (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, locality_id INT NOT NULL, name VARCHAR(200) NOT NULL, description VARCHAR(255) NOT NULL, photo LONGBLOB NOT NULL, gps VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2C4207988823A92 (locality_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route_item (route_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_B25E249B34ECB4E6 (route_id), INDEX IDX_B25E249B126F525E (item_id), PRIMARY KEY(route_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE locality ADD CONSTRAINT FK_E1D6B8E6E946114A FOREIGN KEY (province_id) REFERENCES province (id)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C4207988823A92 FOREIGN KEY (locality_id) REFERENCES locality (id)');
        $this->addSql('ALTER TABLE route_item ADD CONSTRAINT FK_B25E249B34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE route_item ADD CONSTRAINT FK_B25E249B126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE locality DROP FOREIGN KEY FK_E1D6B8E6E946114A');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C4207988823A92');
        $this->addSql('ALTER TABLE route_item DROP FOREIGN KEY FK_B25E249B34ECB4E6');
        $this->addSql('ALTER TABLE route_item DROP FOREIGN KEY FK_B25E249B126F525E');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE locality');
        $this->addSql('DROP TABLE name');
        $this->addSql('DROP TABLE province');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE route_item');
    }
}
