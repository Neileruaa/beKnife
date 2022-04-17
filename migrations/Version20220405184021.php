<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220405184021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE couteau_outil (couteau_id INT NOT NULL, outil_id INT NOT NULL, INDEX IDX_3B21747B9F9D7E24 (couteau_id), INDEX IDX_3B21747B3ED89C80 (outil_id), PRIMARY KEY(couteau_id, outil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE couteau_outil ADD CONSTRAINT FK_3B21747B9F9D7E24 FOREIGN KEY (couteau_id) REFERENCES couteau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE couteau_outil ADD CONSTRAINT FK_3B21747B3ED89C80 FOREIGN KEY (outil_id) REFERENCES outil (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE couteau_outil');
    }
}
