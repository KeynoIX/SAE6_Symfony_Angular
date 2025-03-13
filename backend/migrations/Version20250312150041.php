<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312150041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_de_paie ADD CONSTRAINT FK_B3236E136BC6FD7D FOREIGN KEY (coach_id_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E6BC6FD7D FOREIGN KEY (coach_id_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD password VARCHAR(255) NOT NULL, ADD specialites JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD tarif_horaire DOUBLE PRECISION DEFAULT NULL, ADD date_inscription DATETIME DEFAULT NULL, ADD niveau_sportif VARCHAR(255) DEFAULT NULL, DROP mot_de_passe, DROP role, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON utilisateur (email)');
        $this->addSql('ALTER TABLE sportif_seance ADD CONSTRAINT FK_A30DF544FFB7083B FOREIGN KEY (sportif_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sportif_seance DROP FOREIGN KEY FK_A30DF544FFB7083B');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E6BC6FD7D');
        $this->addSql('ALTER TABLE fiche_de_paie DROP FOREIGN KEY FK_B3236E136BC6FD7D');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD role VARCHAR(255) NOT NULL, DROP roles, DROP specialites, DROP tarif_horaire, DROP date_inscription, DROP niveau_sportif, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password mot_de_passe VARCHAR(255) NOT NULL');
    }
}
