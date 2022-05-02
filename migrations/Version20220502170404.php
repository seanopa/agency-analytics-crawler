<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502170404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aa_queue (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', envelope VARCHAR(5000) NOT NULL, handled TINYINT(1) DEFAULT 0, delivered_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aa_crawler_job_stats RENAME INDEX idx_3a59ff39ada40271 TO IDX_EDAC5FE2ADA40271');
        $this->addSql('ALTER TABLE aa_links_crawler_tracking RENAME INDEX idx_bcca8f2e9502f0b TO IDX_BCCA8F2E93BF9890');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE aa_queue');
        $this->addSql('ALTER TABLE aa_crawler_job_stats RENAME INDEX idx_edac5fe2ada40271 TO IDX_3A59FF39ADA40271');
        $this->addSql('ALTER TABLE aa_links_crawler_tracking RENAME INDEX idx_bcca8f2e93bf9890 TO IDX_BCCA8F2E9502F0B');
    }
}
