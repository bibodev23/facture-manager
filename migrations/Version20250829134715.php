<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250829134715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09DB8BBA08 ON customer (siren)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E0926E94372 ON customer (siret)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09EF699620 ON customer (tva)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_81398E09DB8BBA08 ON customer');
        $this->addSql('DROP INDEX UNIQ_81398E0926E94372 ON customer');
        $this->addSql('DROP INDEX UNIQ_81398E09EF699620 ON customer');
    }
}
