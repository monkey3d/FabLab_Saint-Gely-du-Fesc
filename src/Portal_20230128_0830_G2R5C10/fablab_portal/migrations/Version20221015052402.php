<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221015052402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_document (category_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_6F130E0D12469DE2 (category_id), INDEX IDX_6F130E0DC33F7837 (document_id), PRIMARY KEY(category_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_document ADD CONSTRAINT FK_6F130E0D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_document ADD CONSTRAINT FK_6F130E0DC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_document DROP FOREIGN KEY FK_6F130E0D12469DE2');
        $this->addSql('ALTER TABLE category_document DROP FOREIGN KEY FK_6F130E0DC33F7837');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_document');
    }
}
