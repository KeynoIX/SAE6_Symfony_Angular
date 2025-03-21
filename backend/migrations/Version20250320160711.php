<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320160711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, seance_id INT NOT NULL, sportif_id INT NOT NULL, presence VARCHAR(10) NOT NULL, INDEX IDX_AB55E24FE3797A94 (seance_id), INDEX IDX_AB55E24FFFB7083B (sportif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FE3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FFFB7083B FOREIGN KEY (sportif_id) REFERENCES sportif (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FE3797A94');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FFFB7083B');
        $this->addSql('DROP TABLE participation');
    }
}
