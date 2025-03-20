<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration consolidée pour créer l'intégralité du schéma.
 */
final class Version202503121XXXXXX extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création du schéma complet : tables utilisateur, coach, sportif, fiche_de_paie, seance, seance_exercice, sportif_seance';
    }

    public function up(Schema $schema): void
    {
        // Création de la table utilisateur
        $this->addSql('
            CREATE TABLE utilisateur (
                id INT AUTO_INCREMENT NOT NULL, 
                nom VARCHAR(255) NOT NULL, 
                prenom VARCHAR(255) NOT NULL, 
                email VARCHAR(180) NOT NULL, 
                roles JSON NOT NULL COMMENT \'(DC2Type:json)\', 
                password VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON utilisateur (email)');

        // Création de la table coach (hérite de utilisateur via id)
        $this->addSql('
            CREATE TABLE coach (
                id INT NOT NULL,
                specialites JSON NOT NULL COMMENT \'(DC2Type:json)\',
                tarif_horaire DOUBLE PRECISION NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        // FK de coach vers utilisateur (1:1)
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_COACH_USER FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');

        // Création de la table sportif (hérite de utilisateur via id)
        $this->addSql('
            CREATE TABLE sportif (
                id INT NOT NULL,
                date_inscription DATETIME NOT NULL,
                niveau_sportif VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        // FK de sportif vers utilisateur (1:1)
        $this->addSql('ALTER TABLE sportif ADD CONSTRAINT FK_SPORTIF_USER FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');

        // Création de la table fiche_de_paie (pour coach)
        $this->addSql('
            CREATE TABLE fiche_de_paie (
                id INT AUTO_INCREMENT NOT NULL,
                coach_id_id INT NOT NULL,
                periode VARCHAR(255) NOT NULL,
                total_heures INT NOT NULL,
                montant_total DOUBLE PRECISION NOT NULL,
                INDEX IDX_FICHE_COACH (coach_id_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        // FK fiche_de_paie vers coach
        $this->addSql('ALTER TABLE fiche_de_paie ADD CONSTRAINT FK_FICHE_COACH FOREIGN KEY (coach_id_id) REFERENCES coach (id)');

        // Création de la table seance (pour coach)
        $this->addSql('
            CREATE TABLE seance (
                id INT AUTO_INCREMENT NOT NULL,
                coach_id_id INT NOT NULL,
                date_heure DATETIME NOT NULL,
                type_seance VARCHAR(255) NOT NULL,
                theme_seance VARCHAR(255) NOT NULL,
                niveau_seance VARCHAR(255) NOT NULL,
                statut VARCHAR(255) NOT NULL,
                INDEX IDX_SEANCE_COACH (coach_id_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        // FK seance vers coach
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_SEANCE_COACH FOREIGN KEY (coach_id_id) REFERENCES coach (id)');

        // Création de la table seance_exercice (table de jointure)
        $this->addSql('
            CREATE TABLE seance_exercice (
                seance_id INT NOT NULL,
                exercice_id INT NOT NULL,
                INDEX IDX_SEANCE_EXERCICE_SEANCE (seance_id),
                INDEX IDX_SEANCE_EXERCICE_EXERCICE (exercice_id),
                PRIMARY KEY(seance_id, exercice_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        // FK seance_exercice vers seance et exercice (on supposera que table exercice sera créée ensuite)
        
        // Création de la table exercice
        $this->addSql('
            CREATE TABLE exercice (
                id INT AUTO_INCREMENT NOT NULL,
                nom VARCHAR(255) NOT NULL,
                description LONGTEXT DEFAULT NULL,
                duree_estimee INT NOT NULL,
                difficulte VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE seance_exercice ADD CONSTRAINT FK_SEANCE_EXERCICE_SEANCE FOREIGN KEY (seance_id) REFERENCES seance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seance_exercice ADD CONSTRAINT FK_SEANCE_EXERCICE_EXERCICE FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE');

        // Création de la table sportif_seance (table de jointure pour sportif et seance)
        $this->addSql('
            CREATE TABLE sportif_seance (
                sportif_id INT NOT NULL,
                seance_id INT NOT NULL,
                INDEX IDX_SPORTIF_SEANCE_SPORTIF (sportif_id),
                INDEX IDX_SPORTIF_SEANCE_SEANCE (seance_id),
                PRIMARY KEY(sportif_id, seance_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        // FK sportif_seance vers sportif et seance
        $this->addSql('ALTER TABLE sportif_seance ADD CONSTRAINT FK_SPORTIF_SEANCE_SPORTIF FOREIGN KEY (sportif_id) REFERENCES sportif (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sportif_seance ADD CONSTRAINT FK_SPORTIF_SEANCE_SEANCE FOREIGN KEY (seance_id) REFERENCES seance (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // Supprimer les tables dans l'ordre inverse pour respecter les dépendances
        $this->addSql('ALTER TABLE sportif_seance DROP FOREIGN KEY FK_SPORTIF_SEANCE_SPORTIF');
        $this->addSql('ALTER TABLE sportif_seance DROP FOREIGN KEY FK_SPORTIF_SEANCE_SEANCE');
        $this->addSql('DROP TABLE sportif_seance');

        $this->addSql('ALTER TABLE seance_exercice DROP FOREIGN KEY FK_SEANCE_EXERCICE_SEANCE');
        $this->addSql('ALTER TABLE seance_exercice DROP FOREIGN KEY FK_SEANCE_EXERCICE_EXERCICE');
        $this->addSql('DROP TABLE seance_exercice');

        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_SEANCE_COACH');
        $this->addSql('DROP TABLE seance');

        $this->addSql('ALTER TABLE fiche_de_paie DROP FOREIGN KEY FK_FICHE_COACH');
        $this->addSql('DROP TABLE fiche_de_paie');

        $this->addSql('ALTER TABLE sportif DROP FOREIGN KEY FK_D28C448DBF396750');
        $this->addSql('DROP TABLE sportif');

        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_COACH_USER');
        $this->addSql('DROP TABLE coach');

        $this->addSql('DROP TABLE exercice');

        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON utilisateur');
        $this->addSql('DROP TABLE utilisateur');
    }
}
