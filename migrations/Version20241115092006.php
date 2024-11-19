<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115092006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal CHANGE birth birth DATE DEFAULT NULL, CHANGE handicap handicap VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE request CHANGE date_fulfilled date_fulfilled DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal CHANGE birth birth DATE DEFAULT \'NULL\', CHANGE handicap handicap VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE request CHANGE date_fulfilled date_fulfilled DATETIME DEFAULT \'NULL\'');
    }
}
