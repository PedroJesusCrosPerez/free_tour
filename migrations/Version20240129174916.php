<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129174916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ratings (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, guide_rating DOUBLE PRECISION NOT NULL, route_rating DOUBLE PRECISION NOT NULL, comments LONGTEXT NOT NULL, INDEX IDX_CEB607C9B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, tour_id INT NOT NULL, datetime DATETIME NOT NULL, number_tickets INT NOT NULL, assistants INT NOT NULL, INDEX IDX_42C8495519EB6921 (client_id), INDEX IDX_42C8495515ED8D43 (tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour (id INT AUTO_INCREMENT NOT NULL, route_id INT NOT NULL, guide_id INT DEFAULT NULL, datetime DATETIME NOT NULL, available TINYINT(1) NOT NULL, INDEX IDX_6AD1F96934ECB4E6 (route_id), INDEX IDX_6AD1F969D7ED1D4B (guide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C9B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495515ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F96934ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F969D7ED1D4B FOREIGN KEY (guide_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE route ADD datetime_start DATETIME NOT NULL, ADD datetime_end DATETIME NOT NULL, ADD capacity INT NOT NULL, ADD programation LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C9B83297E7');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495515ED8D43');
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F96934ECB4E6');
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F969D7ED1D4B');
        $this->addSql('DROP TABLE ratings');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE tour');
        $this->addSql('ALTER TABLE route DROP datetime_start, DROP datetime_end, DROP capacity, DROP programation');
    }
}
