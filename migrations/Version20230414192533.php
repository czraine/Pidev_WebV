<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414192533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9715E6E16154E4ED ON favplaces (Place_Id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9715E6E1A6816575 ON favplaces (id_User)');
        $this->addSql('ALTER TABLE placereviews CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'NULL\', CHANGE Review_date Review_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE placetovisit CHANGE Tickets_Price Tickets_Price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'NULL\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'NULL\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'NULL\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'NULL\', CHANGE Nationality Nationality VARCHAR(100) DEFAULT \'NULL\', CHANGE Langue Langue VARCHAR(30) DEFAULT \'NULL\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX UNIQ_9715E6E16154E4ED ON Favplaces');
        $this->addSql('DROP INDEX UNIQ_9715E6E1A6816575 ON Favplaces');
        $this->addSql('ALTER TABLE placeReviews CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'\'\'NULL\'\'\', CHANGE Review_date Review_date DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE placetovisit CHANGE Tickets_Price Tickets_Price DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'\'\'NULL\'\'\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Nationality Nationality VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Langue Langue VARCHAR(30) DEFAULT \'\'\'NULL\'\'\'');
    }
}
