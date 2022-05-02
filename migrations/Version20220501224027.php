<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220501224027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            ALTER TABLE aa_links_crawler_tracking ADD page_load_time DOUBLE PRECISION DEFAULT NULL AFTER message, 
            ADD internal_links_count INT DEFAULT NULL AFTER page_load_time, 
            ADD external_links_count INT DEFAULT NULL AFTER internal_links_count, 
            ADD image_count INT DEFAULT NULL AFTER external_links_count
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aa_links_crawler_tracking DROP page_load_time, DROP internal_links_count, DROP external_links_count, DROP image_count');
    }
}
