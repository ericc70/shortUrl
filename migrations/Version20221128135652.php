<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128135652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE url ADD user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE url ADD CONSTRAINT FK_F47645AE9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F47645AE9D86650F ON url (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE url DROP CONSTRAINT FK_F47645AE9D86650F');
        $this->addSql('DROP INDEX IDX_F47645AE9D86650F');
        $this->addSql('ALTER TABLE url DROP user_id_id');
    }
}
