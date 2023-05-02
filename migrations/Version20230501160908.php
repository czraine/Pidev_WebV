<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501160908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY fk_qsd,qsk');
        $this->addSql('ALTER TABLE place_reviews DROP FOREIGN KEY hergeba3');
        $this->addSql('ALTER TABLE place_reviews DROP FOREIGN KEY place_reviews_ibfk_1');
        $this->addSql('DROP TABLE appointment_request');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE diagnostic');
        $this->addSql('DROP TABLE fiche_patient');
        $this->addSql('DROP TABLE place_reviews');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_tag');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE resultat');
        $this->addSql('DROP TABLE stars');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP INDEX place_Id ON review');
        $this->addSql('ALTER TABLE review CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'NULL\', CHANGE place_Id place_Id INT DEFAULT NULL, CHANGE id_User idUser INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX place_Id ON review (place_Id)');
        $this->addSql('ALTER TABLE shopping_cart MODIFY id_Cart INT NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart MODIFY id_Cart INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON shopping_cart');
        $this->addSql('ALTER TABLE shopping_cart CHANGE user_id user_id INT DEFAULT NULL, CHANGE id_Product id_Product INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shopping_cart ADD PRIMARY KEY (id_cart)');
        $this->addSql('ALTER TABLE user_favsplaces MODIFY id_Favs INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON user_favsplaces');
        $this->addSql('ALTER TABLE user_favsplaces CHANGE id_Favs id_Favs INT NOT NULL');
        $this->addSql('ALTER TABLE user_favsplaces ADD PRIMARY KEY (id_Favs, id_Place, id_User)');
        $this->addSql('ALTER TABLE users CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'NULL\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'NULL\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'NULL\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'NULL\', CHANGE Nationality Nationality VARCHAR(50) DEFAULT \'NULL\', CHANGE Langue Langue VARCHAR(50) DEFAULT \'NULL\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment_request (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, lastname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, birthday DATE NOT NULL, gender VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, phonenumber VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, new_old VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, appointmentprocedure VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, appointmentdate DATE NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, lien VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, appointmentime TIME NOT NULL, id_patient_id INT NOT NULL, status VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_AAB4BDB7CE0312AE (id_patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, author_id INT NOT NULL, post_id INT NOT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, published_at DATETIME NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE diagnostic (id INT NOT NULL, age VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, overweight VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, cigarettes VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, recently_injured VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, high_cholesterol VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hyper_tension VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, diabetes VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, symptoms LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE fiche_patient (id INT NOT NULL, name VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, symptome LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, medicament LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, progres LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rendez_vous_id INT NOT NULL, UNIQUE INDEX UNIQ_2DB8C3191EF7EAA (rendez_vous_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE place_reviews (Review_id INT AUTO_INCREMENT NOT NULL, Place_Name VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Rating INT NOT NULL, Review_txt VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, place_Id INT NOT NULL, Review_date DATE NOT NULL, id_User INT NOT NULL, INDEX place_Id (place_Id), INDEX hergeba3 (id_User), PRIMARY KEY(Review_id, place_Id, id_User)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, author_id INT NOT NULL, title LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, summary LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, published_at DATETIME NOT NULL, INDEX IDX_5A8A6C8DF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post_tag (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_5ACE3AF04B89032C (post_id), INDEX IDX_5ACE3AF0BAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE promotion (id INT NOT NULL, date_debut_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', pourcentage INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (id INT NOT NULL, id_patient_id INT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, message LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_CE606404CE0312AE (id_patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reponse (id INT NOT NULL, id_reclamation_id INT NOT NULL, reponse LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_5FB6DEC7100D1FDF (id_reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE resultat (id INT NOT NULL, diagnostic_id INT DEFAULT NULL, action VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, possibility INT NOT NULL, doctor_note VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, urgency VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_E7DB5DE2224CCA91 (diagnostic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE stars (id INT NOT NULL, idpost_id INT DEFAULT NULL, u_id INT DEFAULT NULL, rate_index INT DEFAULT NULL, INDEX IDX_11DC02C948D5142 (idpost_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_389B7835E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_qsd,qsk FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place_reviews ADD CONSTRAINT hergeba3 FOREIGN KEY (id_User) REFERENCES user (id_User)');
        $this->addSql('ALTER TABLE place_reviews ADD CONSTRAINT place_reviews_ibfk_1 FOREIGN KEY (place_Id) REFERENCES placetovisit (Place_Id)');
        $this->addSql('DROP INDEX place_Id ON review');
        $this->addSql('ALTER TABLE review CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT NULL, CHANGE place_Id place_Id INT NOT NULL, CHANGE idUser id_User INT NOT NULL');
        $this->addSql('CREATE INDEX place_Id ON review (place_Id)');
        $this->addSql('ALTER TABLE shopping_cart MODIFY id_cart INT NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart MODIFY id_cart INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON shopping_cart');
        $this->addSql('ALTER TABLE shopping_cart CHANGE user_id user_id INT NOT NULL, CHANGE id_Product id_Product INT NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart ADD PRIMARY KEY (id_Cart, id_Product, user_id)');
        $this->addSql('ALTER TABLE users CHANGE lang1 lang1 VARCHAR(120) DEFAULT NULL, CHANGE lang2 lang2 VARCHAR(100) DEFAULT NULL, CHANGE lang3 lang3 VARCHAR(100) DEFAULT NULL, CHANGE Cityname Cityname VARCHAR(100) DEFAULT NULL, CHANGE Nationality Nationality VARCHAR(50) DEFAULT NULL, CHANGE Langue Langue VARCHAR(50) DEFAULT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON user_favsplaces');
        $this->addSql('ALTER TABLE user_favsplaces CHANGE id_Favs id_Favs INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user_favsplaces ADD PRIMARY KEY (id_Favs, id_User, id_Place)');
    }
}
