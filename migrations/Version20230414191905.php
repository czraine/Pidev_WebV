<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414191905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A6816575');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6982286BB');
        $this->addSql('ALTER TABLE userfavsplaces DROP FOREIGN KEY FK_43FC41005B593DF9');
        $this->addSql('ALTER TABLE userfavsplaces DROP FOREIGN KEY FK_43FC4100A6816575');
        $this->addSql('ALTER TABLE user_favsplaces DROP FOREIGN KEY FK_2BDED01D5B593DF9');
        $this->addSql('ALTER TABLE user_favsplaces DROP FOREIGN KEY FK_2BDED01DA6816575');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE userfavsplaces');
        $this->addSql('DROP TABLE user_favsplaces');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9715E6E16154E4ED ON favplaces (Place_Id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9715E6E1A6816575 ON favplaces (id_User)');
        $this->addSql('ALTER TABLE placereviews CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'NULL\', CHANGE Review_date Review_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE placetovisit CHANGE Tickets_Price Tickets_Price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'NULL\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'NULL\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'NULL\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'NULL\', CHANGE Nationality Nationality VARCHAR(100) DEFAULT \'NULL\', CHANGE Langue Langue VARCHAR(30) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE review (Review_id INT AUTO_INCREMENT NOT NULL, Place_Name VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, Rating INT NOT NULL, Review_txt VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'\'\'NULL\'\'\' COLLATE `utf8mb4_unicode_ci`, Review_date DATE NOT NULL, place_Id INT DEFAULT NULL, id_User INT DEFAULT NULL, UNIQUE INDEX place_id_2 (place_Id), INDEX reviewPlace (id_User), UNIQUE INDEX place_Id_3 (place_Id), PRIMARY KEY(Review_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE userfavsplaces (id INT AUTO_INCREMENT NOT NULL, id_Place INT DEFAULT NULL, id_User INT DEFAULT NULL, INDEX favplace_idplace (id_Place), INDEX favuser_idUser (id_User), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_favsplaces (id INT AUTO_INCREMENT NOT NULL, id_Place INT DEFAULT NULL, id_User INT DEFAULT NULL, INDEX favplace_idplace (id_Place), INDEX favuser_idUser (id_User), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A6816575 FOREIGN KEY (id_User) REFERENCES user (id_User)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6982286BB FOREIGN KEY (place_Id) REFERENCES placetovisit (Place_Id)');
        $this->addSql('ALTER TABLE userfavsplaces ADD CONSTRAINT FK_43FC41005B593DF9 FOREIGN KEY (id_Place) REFERENCES placetovisit (Place_Id)');
        $this->addSql('ALTER TABLE userfavsplaces ADD CONSTRAINT FK_43FC4100A6816575 FOREIGN KEY (id_User) REFERENCES user (id_User)');
        $this->addSql('ALTER TABLE user_favsplaces ADD CONSTRAINT FK_2BDED01D5B593DF9 FOREIGN KEY (id_Place) REFERENCES placetovisit (Place_Id)');
        $this->addSql('ALTER TABLE user_favsplaces ADD CONSTRAINT FK_2BDED01DA6816575 FOREIGN KEY (id_User) REFERENCES user (id_User)');
        $this->addSql('DROP INDEX UNIQ_9715E6E16154E4ED ON Favplaces');
        $this->addSql('DROP INDEX UNIQ_9715E6E1A6816575 ON Favplaces');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE placeReviews CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'\'\'NULL\'\'\', CHANGE Review_date Review_date DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE placetovisit CHANGE Tickets_Price Tickets_Price DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'\'\'NULL\'\'\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Nationality Nationality VARCHAR(100) DEFAULT \'\'\'NULL\'\'\', CHANGE Langue Langue VARCHAR(30) DEFAULT \'\'\'NULL\'\'\'');
    }
}
