<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417052715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE placereviews CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'NULL\', CHANGE Review_date Review_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE placetovisit CHANGE Tickets_Price Tickets_Price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE lang1 lang1 VARCHAR(120) DEFAULT NULL, CHANGE lang2 lang2 VARCHAR(100) DEFAULT NULL, CHANGE lang3 lang3 VARCHAR(100) DEFAULT NULL, CHANGE Cityname Cityname VARCHAR(100) DEFAULT NULL, CHANGE Nationality Nationality VARCHAR(100) DEFAULT NULL, CHANGE Langue Langue VARCHAR(30) DEFAULT NULL, CHANGE dateBeg dateBeg DATE DEFAULT NULL, CHANGE dateEnd dateEnd DATE DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE placeReviews CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'\'\'NULL\'\'\', CHANGE Review_date Review_date DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE placetovisit CHANGE Tickets_Price Tickets_Price DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'NULL\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'NULL\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'NULL\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'NULL\', CHANGE Nationality Nationality VARCHAR(100) DEFAULT \'NULL\', CHANGE Langue Langue VARCHAR(30) DEFAULT \'NULL\', CHANGE dateBeg dateBeg DATE DEFAULT \'NULL\', CHANGE dateEnd dateEnd DATE DEFAULT \'NULL\'');
    }
}
