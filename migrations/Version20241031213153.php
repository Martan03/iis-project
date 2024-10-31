<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031213153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrator (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, species VARCHAR(255) NOT NULL, breed VARCHAR(255) NOT NULL, birth DATE DEFAULT NULL, gender VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, height DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, discovery_date DATETIME NOT NULL, discovery_place VARCHAR(255) NOT NULL, handicap VARCHAR(255) DEFAULT NULL, castration TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caregiver (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examination (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, veterinary_id INT NOT NULL, date DATETIME NOT NULL, result LONGTEXT NOT NULL, INDEX IDX_CCDAABC58E962C16 (animal_id), INDEX IDX_CCDAABC5D954EB99 (veterinary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, walk_id INT DEFAULT NULL, volunteer_id INT DEFAULT NULL, state VARCHAR(255) NOT NULL, INDEX IDX_62A8A7A75EEE1B48 (walk_id), INDEX IDX_62A8A7A78EFAB6B1 (volunteer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE request (id INT AUTO_INCREMENT NOT NULL, examination_id INT DEFAULT NULL, veterinary_id INT NOT NULL, caregiver_id INT NOT NULL, animal_id INT DEFAULT NULL, date_created DATETIME NOT NULL, date_fulfilled DATETIME DEFAULT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_3B978F9FDAD0CFBF (examination_id), INDEX IDX_3B978F9FD954EB99 (veterinary_id), INDEX IDX_3B978F9F41946261 (caregiver_id), INDEX IDX_3B978F9F8E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE veterinary (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteer (id INT NOT NULL, verified TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE walk (id INT AUTO_INCREMENT NOT NULL, animal_id INT DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, INDEX IDX_8D917A558E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE caregiver ADD CONSTRAINT FK_27A9DEC5BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE examination ADD CONSTRAINT FK_CCDAABC58E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE examination ADD CONSTRAINT FK_CCDAABC5D954EB99 FOREIGN KEY (veterinary_id) REFERENCES veterinary (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A75EEE1B48 FOREIGN KEY (walk_id) REFERENCES walk (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A78EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FDAD0CFBF FOREIGN KEY (examination_id) REFERENCES examination (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FD954EB99 FOREIGN KEY (veterinary_id) REFERENCES veterinary (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F41946261 FOREIGN KEY (caregiver_id) REFERENCES caregiver (id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9F8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE veterinary ADD CONSTRAINT FK_8B49EF57BF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDBBF396750 FOREIGN KEY (id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE walk ADD CONSTRAINT FK_8D917A558E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651BF396750');
        $this->addSql('ALTER TABLE caregiver DROP FOREIGN KEY FK_27A9DEC5BF396750');
        $this->addSql('ALTER TABLE examination DROP FOREIGN KEY FK_CCDAABC58E962C16');
        $this->addSql('ALTER TABLE examination DROP FOREIGN KEY FK_CCDAABC5D954EB99');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A75EEE1B48');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A78EFAB6B1');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FDAD0CFBF');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9FD954EB99');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F41946261');
        $this->addSql('ALTER TABLE request DROP FOREIGN KEY FK_3B978F9F8E962C16');
        $this->addSql('ALTER TABLE veterinary DROP FOREIGN KEY FK_8B49EF57BF396750');
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDBBF396750');
        $this->addSql('ALTER TABLE walk DROP FOREIGN KEY FK_8D917A558E962C16');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE caregiver');
        $this->addSql('DROP TABLE examination');
        $this->addSql('DROP TABLE registration');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE veterinary');
        $this->addSql('DROP TABLE volunteer');
        $this->addSql('DROP TABLE walk');
    }
}
