<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303133257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations ADD date_debut DATE DEFAULT NULL, ADD date_fin DATE DEFAULT NULL, DROP date_debut_sejour, DROP date_fin_sejour, CHANGE date_reservation date_reservation DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservations ADD date_debut_sejour DATE DEFAULT NULL, ADD date_fin_sejour DATE DEFAULT NULL, DROP date_debut, DROP date_fin, CHANGE date_reservation date_reservation DATE DEFAULT NULL');
    }
}
