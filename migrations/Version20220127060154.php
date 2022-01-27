<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127060154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ADD correctanswer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EF172D813 FOREIGN KEY (correctanswer_id) REFERENCES answer (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6F7494EF172D813 ON question (correctanswer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EF172D813');
        $this->addSql('DROP INDEX UNIQ_B6F7494EF172D813 ON question');
        $this->addSql('ALTER TABLE question DROP correctanswer_id');
    }
}
