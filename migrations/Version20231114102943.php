<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114102943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etape ADD adresse_depart VARCHAR(255) NOT NULL, ADD ville_depart VARCHAR(255) NOT NULL, ADD adresse_arrivee VARCHAR(255) NOT NULL, ADD code_postal_arrivee INT NOT NULL, ADD ville_arrivee VARCHAR(255) NOT NULL, DROP type, DROP adresse, DROP ville, DROP latitude, DROP longitude, CHANGE code_postal code_postal_depart INT NOT NULL');
        $this->addSql('ALTER TABLE trajet CHANGE date_depart date_depart VARCHAR(255) NOT NULL, CHANGE heure_depart heure_depart VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etape ADD type VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD code_postal INT NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD latitude VARCHAR(50) NOT NULL, ADD longitude VARCHAR(50) NOT NULL, DROP adresse_depart, DROP code_postal_depart, DROP ville_depart, DROP adresse_arrivee, DROP code_postal_arrivee, DROP ville_arrivee');
        $this->addSql('ALTER TABLE trajet CHANGE date_depart date_depart DATE NOT NULL, CHANGE heure_depart heure_depart TIME NOT NULL');
    }
}
