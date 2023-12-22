<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220170720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, models_id INT NOT NULL, number_of_places INT NOT NULL, number_of_small_bags INT NOT NULL, number_of_big_bags INT NOT NULL, INDEX IDX_773DE69DA76ED395 (user_id), INDEX IDX_773DE69DD966BF49 (models_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_659DF2AAF624B39D (sender_id), INDEX IDX_659DF2AAE92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE com (id_com INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, trip_id INT NOT NULL, com LONGTEXT NOT NULL, date_com DATE NOT NULL, note_com INT NOT NULL, INDEX IDX_64B6C6E6A76ED395 (user_id), INDEX IDX_64B6C6E6A5BC2E0E (trip_id), PRIMARY KEY(id_com)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_C53D045FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_cars (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, image_url VARCHAR(255) NOT NULL, INDEX IDX_67FDF78AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE models (id INT AUTO_INCREMENT NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, trip_id INT NOT NULL, status VARCHAR(255) NOT NULL, reservation_date DATETIME NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955A5BC2E0E (trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, trip_id INT NOT NULL, departure_address VARCHAR(255) NOT NULL, departure_zip_code INT NOT NULL, departure_city VARCHAR(255) NOT NULL, arrival_address VARCHAR(255) NOT NULL, arrival_zip_code INT NOT NULL, arrival_city VARCHAR(255) NOT NULL, INDEX IDX_43B9FE3CA5BC2E0E (trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, user_id INT NOT NULL, price INT NOT NULL, smoker TINYINT(1) NOT NULL, silence TINYINT(1) NOT NULL, music TINYINT(1) NOT NULL, pets TINYINT(1) NOT NULL, departure_date VARCHAR(255) NOT NULL, departure_time VARCHAR(255) NOT NULL, INDEX IDX_7656F53BC3C6F69F (car_id), INDEX IDX_7656F53BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, username VARCHAR(255) DEFAULT NULL, credit_coin INT NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', address VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, date_of_birth VARCHAR(255) DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, biography LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DD966BF49 FOREIGN KEY (models_id) REFERENCES models (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAE92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E6A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image_cars ADD CONSTRAINT FK_67FDF78AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3CA5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA76ED395');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DD966BF49');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAF624B39D');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAE92F8F78');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E6A76ED395');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E6A5BC2E0E');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA76ED395');
        $this->addSql('ALTER TABLE image_cars DROP FOREIGN KEY FK_67FDF78AA76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A5BC2E0E');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3CA5BC2E0E');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BC3C6F69F');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BA76ED395');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE com');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_cars');
        $this->addSql('DROP TABLE models');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE user');
    }
}
