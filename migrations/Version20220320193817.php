<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220320193817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postcode MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE postcode DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE postcode DROP id');
        $this->addSql('ALTER TABLE postcode ADD PRIMARY KEY (postcode)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE postcode ADD id INT AUTO_INCREMENT NOT NULL, CHANGE postcode postcode VARCHAR(8) NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
