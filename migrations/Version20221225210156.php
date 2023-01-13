<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221225210156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administration_site ADD titre VARCHAR(255) DEFAULT NULL, ADD soustitre VARCHAR(255) DEFAULT NULL, ADD fonctionnement VARCHAR(255) DEFAULT NULL, ADD sousfonctionnement VARCHAR(255) DEFAULT NULL, ADD titre1 VARCHAR(255) DEFAULT NULL, ADD soustitre1 LONGTEXT DEFAULT NULL, ADD titre2 VARCHAR(255) DEFAULT NULL, ADD soustitre2 LONGTEXT NOT NULL, ADD titre3 VARCHAR(255) DEFAULT NULL, ADD soustitre3 LONGTEXT DEFAULT NULL, ADD description1 VARCHAR(255) DEFAULT NULL, ADD sousdescription1 LONGTEXT DEFAULT NULL, ADD description2 VARCHAR(255) DEFAULT NULL, ADD sousdescription2 LONGTEXT DEFAULT NULL, ADD contact LONGTEXT DEFAULT NULL, ADD localisation LONGTEXT DEFAULT NULL, ADD qui_somme_nous LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administration_site DROP titre, DROP soustitre, DROP fonctionnement, DROP sousfonctionnement, DROP titre1, DROP soustitre1, DROP titre2, DROP soustitre2, DROP titre3, DROP soustitre3, DROP description1, DROP sousdescription1, DROP description2, DROP sousdescription2, DROP contact, DROP localisation, DROP qui_somme_nous');
    }
}
