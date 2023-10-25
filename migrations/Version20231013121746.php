<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231013121746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE com (id_com INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, trajet_id INT NOT NULL, com LONGTEXT NOT NULL, date_com DATE NOT NULL, note_com INT NOT NULL, INDEX IDX_64B6C6E6FB88E14F (utilisateur_id), INDEX IDX_64B6C6E6D12A823 (trajet_id), PRIMARY KEY(id_com)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dial (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, utilisateur1_id INT NOT NULL, message LONGTEXT NOT NULL, sujet VARCHAR(255) NOT NULL, datetime_dial DATETIME NOT NULL, INDEX IDX_EAE43872FB88E14F (utilisateur_id), INDEX IDX_EAE4387230F4F973 (utilisateur1_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape (id INT AUTO_INCREMENT NOT NULL, trajet_id INT NOT NULL, type VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal INT NOT NULL, ville VARCHAR(255) NOT NULL, longitude NUMERIC(10, 0) NOT NULL, latitude NUMERIC(10, 0) NOT NULL, INDEX IDX_285F75DDD12A823 (trajet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, voiture_id INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_C53D045F181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modeles (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, trajet_id INT NOT NULL, statut VARCHAR(255) NOT NULL, date_reservation DATETIME NOT NULL, INDEX IDX_42C84955FB88E14F (utilisateur_id), INDEX IDX_42C84955D12A823 (trajet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trajet (id INT AUTO_INCREMENT NOT NULL, voiture_id INT NOT NULL, utilisateur_id INT NOT NULL, prix INT NOT NULL, fumeur TINYINT(1) NOT NULL, silence TINYINT(1) NOT NULL, musique TINYINT(1) NOT NULL, animaux TINYINT(1) NOT NULL, date_depart DATE NOT NULL, heure_depart TIME NOT NULL, INDEX IDX_2B5BA98C181A8BA (voiture_id), INDEX IDX_2B5BA98CFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, date_inscription DATETIME NOT NULL, avatar VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(255) DEFAULT NULL, credit_jeton INT NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, modeles_id INT NOT NULL, nbre_de_places INT NOT NULL, nbre_petits_bagages INT NOT NULL, nbre_grands_bagages INT NOT NULL, INDEX IDX_E9E2810FFB88E14F (utilisateur_id), INDEX IDX_E9E2810F708408C (modeles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E6FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE com ADD CONSTRAINT FK_64B6C6E6D12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id)');
        $this->addSql('ALTER TABLE dial ADD CONSTRAINT FK_EAE43872FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE dial ADD CONSTRAINT FK_EAE4387230F4F973 FOREIGN KEY (utilisateur1_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DDD12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D12A823 FOREIGN KEY (trajet_id) REFERENCES trajet (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98C181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F708408C FOREIGN KEY (modeles_id) REFERENCES modeles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E6FB88E14F');
        $this->addSql('ALTER TABLE com DROP FOREIGN KEY FK_64B6C6E6D12A823');
        $this->addSql('ALTER TABLE dial DROP FOREIGN KEY FK_EAE43872FB88E14F');
        $this->addSql('ALTER TABLE dial DROP FOREIGN KEY FK_EAE4387230F4F973');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DDD12A823');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F181A8BA');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FB88E14F');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D12A823');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98C181A8BA');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CFB88E14F');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FFB88E14F');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F708408C');
        $this->addSql('DROP TABLE com');
        $this->addSql('DROP TABLE dial');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE modeles');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE trajet');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE voiture');
    }
}
