<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430132246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place_reviews DROP FOREIGN KEY hergeba3');
        $this->addSql('ALTER TABLE place_reviews DROP FOREIGN KEY place_reviews_ibfk_1');
        $this->addSql('DROP TABLE circuit');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP TABLE place_reviews');
        $this->addSql('ALTER TABLE produit CHANGE status status VARCHAR(10) DEFAULT \'\'\'Available\'\'\' NOT NULL');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY fk_user_rep');
        $this->addSql('ALTER TABLE reports CHANGE Report_Id Report_Id INT NOT NULL, CHANGE Incident_Location Incident_Location VARCHAR(30) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT FK_F11FA745A6816575 FOREIGN KEY (id_User) REFERENCES user (id_User)');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY review_ibfk_2');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY reviewPlace');
        $this->addSql('DROP INDEX place_Id_4 ON review');
        $this->addSql('DROP INDEX place_id_2 ON review');
        $this->addSql('DROP INDEX reviewPlace ON review');
        $this->addSql('DROP INDEX place_Id_3 ON review');
        $this->addSql('DROP INDEX place_Id ON review');
        $this->addSql('ALTER TABLE review CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT \'NULL\', CHANGE place_Id place_Id INT DEFAULT NULL, CHANGE id_User idUser INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6982286BB FOREIGN KEY (place_Id) REFERENCES placetovisit (Place_Id)');
        $this->addSql('CREATE UNIQUE INDEX place_Id ON review (place_Id)');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY shop_cart_ibfk_1');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY shopping_cart_ibfk_1');
        $this->addSql('ALTER TABLE shopping_cart CHANGE id_Cart id_Cart INT NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6C26A9441 FOREIGN KEY (id_Product) REFERENCES produit (id_Produit)');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id_User)');
        $this->addSql('ALTER TABLE user CHANGE User_FirstName User_FirstName VARCHAR(30) NOT NULL, CHANGE User_lastName User_lastName VARCHAR(30) NOT NULL, CHANGE User_mail User_mail VARCHAR(30) NOT NULL, CHANGE User_phone User_phone INT NOT NULL, CHANGE Username Username VARCHAR(30) NOT NULL, CHANGE Password Password VARCHAR(255) NOT NULL, CHANGE role role VARCHAR(30) NOT NULL, CHANGE dateBeg dateBeg DATE DEFAULT NULL, CHANGE dateEnd dateEnd DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE user_favsplaces MODIFY id_Favs INT NOT NULL');
        $this->addSql('ALTER TABLE user_favsplaces DROP FOREIGN KEY favuser_idUser');
        $this->addSql('DROP INDEX id_User ON user_favsplaces');
        $this->addSql('DROP INDEX `primary` ON user_favsplaces');
        $this->addSql('ALTER TABLE user_favsplaces CHANGE id_Favs id_Favs INT NOT NULL');
        $this->addSql('ALTER TABLE user_favsplaces ADD CONSTRAINT FK_2BDED01DA6816575 FOREIGN KEY (id_User) REFERENCES user (id_User)');
        $this->addSql('ALTER TABLE user_favsplaces ADD PRIMARY KEY (id_Favs, id_Place, id_User)');
        $this->addSql('ALTER TABLE users CHANGE lang1 lang1 VARCHAR(120) DEFAULT \'NULL\', CHANGE lang2 lang2 VARCHAR(100) DEFAULT \'NULL\', CHANGE lang3 lang3 VARCHAR(100) DEFAULT \'NULL\', CHANGE Cityname Cityname VARCHAR(100) DEFAULT \'NULL\', CHANGE Nationality Nationality VARCHAR(50) DEFAULT \'NULL\', CHANGE Langue Langue VARCHAR(50) DEFAULT \'NULL\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE circuit (nc INT NOT NULL, vdep VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, varr VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix DOUBLE PRECISION NOT NULL, duree INT NOT NULL, nom VARCHAR(60) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(2000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, imageSrc VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(nc)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE etape (rang INT NOT NULL, nc INT AUTO_INCREMENT NOT NULL, ville VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, jr INT NOT NULL, programme VARCHAR(2000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX nc (nc), PRIMARY KEY(rang)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE place_reviews (Review_id INT AUTO_INCREMENT NOT NULL, Place_Name VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Rating INT NOT NULL, Review_txt VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, place_Id INT NOT NULL, Review_date DATE NOT NULL, id_User INT NOT NULL, INDEX hergeba3 (id_User), INDEX place_Id (place_Id), PRIMARY KEY(Review_id, place_Id, id_User)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE place_reviews ADD CONSTRAINT hergeba3 FOREIGN KEY (id_User) REFERENCES user (id_User) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place_reviews ADD CONSTRAINT place_reviews_ibfk_1 FOREIGN KEY (place_Id) REFERENCES placetovisit (Place_Id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit CHANGE status status VARCHAR(10) DEFAULT \'Available\' NOT NULL');
        $this->addSql('ALTER TABLE reports DROP FOREIGN KEY FK_F11FA745A6816575');
        $this->addSql('ALTER TABLE reports CHANGE Report_Id Report_Id INT AUTO_INCREMENT NOT NULL, CHANGE Incident_Location Incident_Location VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE reports ADD CONSTRAINT fk_user_rep FOREIGN KEY (id_User) REFERENCES user (id_User) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6982286BB');
        $this->addSql('DROP INDEX place_Id ON review');
        $this->addSql('ALTER TABLE review CHANGE Review_txt Review_txt VARCHAR(120) DEFAULT NULL, CHANGE place_Id place_Id INT NOT NULL, CHANGE idUser id_User INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT review_ibfk_2 FOREIGN KEY (place_Id) REFERENCES placetovisit (Place_Id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT reviewPlace FOREIGN KEY (id_User) REFERENCES user (id_User) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX place_Id_4 ON review (place_Id)');
        $this->addSql('CREATE UNIQUE INDEX place_id_2 ON review (place_Id)');
        $this->addSql('CREATE INDEX reviewPlace ON review (id_User)');
        $this->addSql('CREATE UNIQUE INDEX place_Id_3 ON review (place_Id)');
        $this->addSql('CREATE INDEX place_Id ON review (place_Id)');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F6C26A9441');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F6A76ED395');
        $this->addSql('ALTER TABLE shopping_cart CHANGE id_Cart id_Cart INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT shop_cart_ibfk_1 FOREIGN KEY (id_Product) REFERENCES produit (id_Produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT shopping_cart_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id_User) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE User_FirstName User_FirstName VARCHAR(30) DEFAULT NULL, CHANGE User_lastName User_lastName VARCHAR(30) DEFAULT NULL, CHANGE User_mail User_mail VARCHAR(30) DEFAULT NULL, CHANGE User_phone User_phone INT DEFAULT NULL, CHANGE Username Username VARCHAR(30) DEFAULT NULL, CHANGE Password Password VARCHAR(255) DEFAULT NULL, CHANGE role role VARCHAR(30) DEFAULT \'guide\', CHANGE dateBeg dateBeg DATE DEFAULT \'CURRENT_TIMESTAMP\', CHANGE dateEnd dateEnd DATE DEFAULT \'CURRENT_TIMESTAMP\'');
        $this->addSql('ALTER TABLE users CHANGE lang1 lang1 VARCHAR(120) DEFAULT NULL, CHANGE lang2 lang2 VARCHAR(100) DEFAULT NULL, CHANGE lang3 lang3 VARCHAR(100) DEFAULT NULL, CHANGE Cityname Cityname VARCHAR(100) DEFAULT NULL, CHANGE Nationality Nationality VARCHAR(50) DEFAULT NULL, CHANGE Langue Langue VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_favsplaces DROP FOREIGN KEY FK_2BDED01DA6816575');
        $this->addSql('DROP INDEX `PRIMARY` ON user_favsplaces');
        $this->addSql('ALTER TABLE user_favsplaces CHANGE id_Favs id_Favs INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user_favsplaces ADD CONSTRAINT favuser_idUser FOREIGN KEY (id_User) REFERENCES user (id_User) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX id_User ON user_favsplaces (id_User)');
        $this->addSql('ALTER TABLE user_favsplaces ADD PRIMARY KEY (id_Favs, id_User, id_Place)');
    }
}
