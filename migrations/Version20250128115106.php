<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128115106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paste ALTER uuid TYPE UUID');
        $this->addSql('ALTER TABLE paste ALTER uuid DROP DEFAULT');
        $this->addSql('ALTER TABLE paste ALTER uuid TYPE UUID');
        $this->addSql('ALTER TABLE paste ALTER access DROP DEFAULT');
        $this->addSql('ALTER TABLE paste ALTER expiration SET NOT NULL');
        $this->addSql('ALTER TABLE paste ALTER lang SET NOT NULL');
        $this->addSql('ALTER TABLE paste ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE paste ALTER created_at SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN paste.uuid IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE paste ALTER uuid TYPE VARCHAR(36)');
        $this->addSql('ALTER TABLE paste ALTER uuid SET DEFAULT \'gen_random_uuid()\'');
        $this->addSql('ALTER TABLE paste ALTER access SET DEFAULT 1');
        $this->addSql('ALTER TABLE paste ALTER expiration DROP NOT NULL');
        $this->addSql('ALTER TABLE paste ALTER lang DROP NOT NULL');
        $this->addSql('ALTER TABLE paste ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE paste ALTER created_at DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN paste.uuid IS NULL');
    }
}
