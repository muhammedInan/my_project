<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200104235304 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE car_city (car_id INT NOT NULL, city_id INT NOT NULL, INDEX IDX_E6311698C3C6F69F (car_id), INDEX IDX_E63116988BAC62AF (city_id), PRIMARY KEY(car_id, city_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car_city ADD CONSTRAINT FK_E6311698C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_city ADD CONSTRAINT FK_E63116988BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE keyword CHANGE car_id car_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE car_city DROP FOREIGN KEY FK_E63116988BAC62AF');
        $this->addSql('DROP TABLE car_city');
        $this->addSql('DROP TABLE city');
        $this->addSql('ALTER TABLE car CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE keyword CHANGE car_id car_id INT DEFAULT NULL');
    }
}
