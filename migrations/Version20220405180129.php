<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220405180129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE couteau (id INT AUTO_INCREMENT NOT NULL, taille_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_196ABA63FF25611A (taille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille_couteau (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, taille INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE couteau ADD CONSTRAINT FK_196ABA63FF25611A FOREIGN KEY (taille_id) REFERENCES taille_couteau (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE couteau DROP FOREIGN KEY FK_196ABA63FF25611A');
        $this->addSql('DROP TABLE couteau');
        $this->addSql('DROP TABLE taille_couteau');
    }
}
