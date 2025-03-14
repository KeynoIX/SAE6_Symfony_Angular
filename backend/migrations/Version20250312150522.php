<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312150522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coach (id INT NOT NULL, specialites JSON NOT NULL COMMENT \'(DC2Type:json)\', tarif_horaire DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sportif (id INT NOT NULL, date_inscription DATETIME NOT NULL, niveau_sportif VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCBF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sportif ADD CONSTRAINT FK_D28C448DBF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fiche_de_paie DROP FOREIGN KEY FK_B3236E136BC6FD7D');
        $this->addSql('ALTER TABLE fiche_de_paie ADD CONSTRAINT FK_B3236E136BC6FD7D FOREIGN KEY (coach_id_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E6BC6FD7D');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E6BC6FD7D FOREIGN KEY (coach_id_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE sportif_seance DROP FOREIGN KEY FK_A30DF544FFB7083B');
        $this->addSql('ALTER TABLE sportif_seance ADD CONSTRAINT FK_A30DF544FFB7083B FOREIGN KEY (sportif_id) REFERENCES sportif (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur DROP specialites, DROP tarif_horaire, DROP date_inscription, DROP niveau_sportif');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_de_paie DROP FOREIGN KEY FK_B3236E136BC6FD7D');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E6BC6FD7D');
        $this->addSql('ALTER TABLE sportif_seance DROP FOREIGN KEY FK_A30DF544FFB7083B');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCBF396750');
        $this->addSql('ALTER TABLE sportif DROP FOREIGN KEY FK_D28C448DBF396750');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE sportif');
        $this->addSql('ALTER TABLE sportif_seance DROP FOREIGN KEY FK_A30DF544FFB7083B');
        $this->addSql('ALTER TABLE sportif_seance ADD CONSTRAINT FK_A30DF544FFB7083B FOREIGN KEY (sportif_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E6BC6FD7D');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E6BC6FD7D FOREIGN KEY (coach_id_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE fiche_de_paie DROP FOREIGN KEY FK_B3236E136BC6FD7D');
        $this->addSql('ALTER TABLE fiche_de_paie ADD CONSTRAINT FK_B3236E136BC6FD7D FOREIGN KEY (coach_id_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD specialites JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD tarif_horaire DOUBLE PRECISION DEFAULT NULL, ADD date_inscription DATETIME DEFAULT NULL, ADD niveau_sportif VARCHAR(255) DEFAULT NULL');
    }
}
