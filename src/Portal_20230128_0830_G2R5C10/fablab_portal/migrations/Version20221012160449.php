<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221012160449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author_document (author_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_37F9A0C3F675F31B (author_id), INDEX IDX_37F9A0C3C33F7837 (document_id), PRIMARY KEY(author_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, summary LONGTEXT NOT NULL, release_date DATE DEFAULT NULL, number_views INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE author_document ADD CONSTRAINT FK_37F9A0C3F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE author_document ADD CONSTRAINT FK_37F9A0C3C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE route DROP params, CHANGE id id VARCHAR(255) NOT NULL, CHANGE date date DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE author_document DROP FOREIGN KEY FK_37F9A0C3F675F31B');
        $this->addSql('ALTER TABLE author_document DROP FOREIGN KEY FK_37F9A0C3C33F7837');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE author_document');
        $this->addSql('DROP TABLE document');
        $this->addSql('ALTER TABLE route ADD params LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE date date DATETIME NOT NULL');
    }
}
