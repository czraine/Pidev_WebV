<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414194052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favplaces DROP FOREIGN KEY FK_9715E6E1A6816575');
        $this->addSql('ALTER TABLE favplaces DROP FOREIGN KEY FK_9715E6E16154E4ED');
        $this->addSql('DROP TABLE favplaces');
        $this->addSql('ALTER TABLE placereviews CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'NULL\', CHANGE Review_date Review_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE placetovisit CHANGE Tickets_Price Tickets_Price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE review CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'NULL\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'NULL\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'NULL\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'NULL\', CHANGE Nationality Nationality VARCHAR(100) DEFAULT \'NULL\', CHANGE Langue Langue VARCHAR(30) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favplaces (id INT AUTO_INCREMENT NOT NULL, Place_Id INT DEFAULT NULL, id_User INT DEFAULT NULL, INDEX favplace_idplace (Place_Id), INDEX favuser_idUser (id_User), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favplaces ADD CONSTRAINT FK_9715E6E1A6816575 FOREIGN KEY (id_User) REFERENCES user (id_User)');
        $this->addSql('ALTER TABLE favplaces ADD CONSTRAINT FK_9715E6E16154E4ED FOREIGN KEY (Place_Id) REFERENCES placetovisit (Place_Id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE placeReviews CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'\'\'NULL\'\'\', CHANGE Review_date Review_date DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE placetovisit CHANGE Tickets_Price Tickets_Price DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE review CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'\'\'NULL\'\'\'');
        $this->addSql('ALTER TABLE user CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'\'\'NULL\'\'\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Nationality Nationality VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Langue Langue VARCHAR(30) DEFAULT \'\'\'NULL\'\'\'');
    }
}
