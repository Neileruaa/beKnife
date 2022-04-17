<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410143605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE couteau_outil DROP FOREIGN KEY FK_3B21747B9F9D7E24');
        $this->addSql('ALTER TABLE couteau_outil DROP FOREIGN KEY FK_3B21747B3ED89C80');
        $this->addSql('ALTER TABLE couteau_outil ADD id INT AUTO_INCREMENT NOT NULL, ADD is_droite TINYINT(1) DEFAULT NULL, ADD is_gauche TINYINT(1) DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE couteau_outil ADD CONSTRAINT FK_3B21747B9F9D7E24 FOREIGN KEY (couteau_id) REFERENCES couteau (id)');
        $this->addSql('ALTER TABLE couteau_outil ADD CONSTRAINT FK_3B21747B3ED89C80 FOREIGN KEY (outil_id) REFERENCES outil (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE couteau_outil MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE couteau_outil DROP FOREIGN KEY FK_3B21747B9F9D7E24');
        $this->addSql('ALTER TABLE couteau_outil DROP FOREIGN KEY FK_3B21747B3ED89C80');
        $this->addSql('ALTER TABLE couteau_outil DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE couteau_outil DROP id, DROP is_droite, DROP is_gauche');
        $this->addSql('ALTER TABLE couteau_outil ADD CONSTRAINT FK_3B21747B9F9D7E24 FOREIGN KEY (couteau_id) REFERENCES couteau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE couteau_outil ADD CONSTRAINT FK_3B21747B3ED89C80 FOREIGN KEY (outil_id) REFERENCES outil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE couteau_outil ADD PRIMARY KEY (couteau_id, outil_id)');
    }
}
