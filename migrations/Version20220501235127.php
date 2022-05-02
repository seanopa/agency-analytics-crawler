<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220501235127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aa_crawler_job_stats (id INT AUTO_INCREMENT NOT NULL, link_id INT DEFAULT NULL, total_pages_crawled INT DEFAULT NULL, image_total INT DEFAULT NULL, internal_link_total INT DEFAULT NULL, external_link_total INT DEFAULT NULL, average_page_load_time DOUBLE PRECISION DEFAULT NULL, average_word_count INT DEFAULT NULL, average_title_length INT DEFAULT NULL, start_date DATE DEFAULT NULL, start_time TIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_3A59FF39ADA40271 (link_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aa_crawler_job_stats ADD CONSTRAINT FK_3A59FF39ADA40271 FOREIGN KEY (link_id) REFERENCES aa_links (id)');
        $this->addSql('ALTER TABLE aa_links_crawler_tracking ADD job_stat_id INT DEFAULT NULL AFTER id');
        $this->addSql('ALTER TABLE aa_links_crawler_tracking ADD CONSTRAINT FK_BCCA8F2E9502F0B FOREIGN KEY (job_stat_id) REFERENCES aa_crawler_job_stats (id)');
        $this->addSql('CREATE INDEX IDX_BCCA8F2E9502F0B ON aa_links_crawler_tracking (job_stat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aa_links_crawler_tracking DROP FOREIGN KEY FK_BCCA8F2E9502F0B');
        $this->addSql('DROP TABLE aa_crawler_stats');
        $this->addSql('DROP INDEX IDX_BCCA8F2E9502F0B ON aa_links_crawler_tracking');
        $this->addSql('ALTER TABLE aa_links_crawler_tracking DROP stat_id');
    }
}
