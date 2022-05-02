<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220501171518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aa_hosts (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX name_UNIQUE (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aa_links (id INT AUTO_INCREMENT NOT NULL, host_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BEDAD43A1FB8D185 (host_id), UNIQUE INDEX host_UNIQUE (host_id, url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aa_links_crawler_tracking (id INT AUTO_INCREMENT NOT NULL, link_id INT DEFAULT NULL, is_started TINYINT(1) NOT NULL, complete_status SMALLINT NOT NULL, http_status INT DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, start_date DATE DEFAULT NULL, start_time TIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BCCA8F2EADA40271 (link_id), INDEX date (start_date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aa_links ADD CONSTRAINT FK_BEDAD43A1FB8D185 FOREIGN KEY (host_id) REFERENCES aa_hosts (id)');
        $this->addSql('ALTER TABLE aa_links_crawler_tracking ADD CONSTRAINT FK_BCCA8F2EADA40271 FOREIGN KEY (link_id) REFERENCES aa_links (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aa_links DROP FOREIGN KEY FK_BEDAD43A1FB8D185');
        $this->addSql('ALTER TABLE aa_links_crawler_tracking DROP FOREIGN KEY FK_BCCA8F2EADA40271');
        $this->addSql('DROP TABLE aa_hosts');
        $this->addSql('DROP TABLE aa_links');
        $this->addSql('DROP TABLE aa_links_crawler_tracking');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
