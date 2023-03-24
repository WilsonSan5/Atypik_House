<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303131250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations ADD date_debut_sejour DATE DEFAULT NULL, ADD date_fin_sejour DATE DEFAULT NULL, DROP date, DROP periode, CHANGE datesejour date_reservation DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations ADD date VARCHAR(255) DEFAULT NULL, ADD periode VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\', ADD datesejour DATE DEFAULT NULL, DROP date_reservation, DROP date_debut_sejour, DROP date_fin_sejour');
    }
}
