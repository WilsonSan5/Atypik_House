<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224182613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, phone VARCHAR(12) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE habitats ADD proprio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE habitats ADD CONSTRAINT FK_B5E492F36B82600 FOREIGN KEY (proprio_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B5E492F36B82600 ON habitats (proprio_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habitats DROP FOREIGN KEY FK_B5E492F36B82600');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_B5E492F36B82600 ON habitats');
        $this->addSql('ALTER TABLE habitats DROP proprio_id');
    }
}
