<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250122114511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE members (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_45A0D2FFE7927C74 ON members (email)');
        $this->addSql('CREATE TABLE paste (id SERIAL NOT NULL, member_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, content VARCHAR(1000) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9C5678987597D3FE ON paste (member_id)');
        $this->addSql('ALTER TABLE paste ADD CONSTRAINT FK_9C5678987597D3FE FOREIGN KEY (member_id) REFERENCES members (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE paste DROP CONSTRAINT FK_9C5678987597D3FE');
        $this->addSql('DROP TABLE members');
        $this->addSql('DROP TABLE paste');
    }
}
