<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240623223910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `ehub_newsletter` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(200) NOT NULL, content LONGTEXT NOT NULL, can_be_published TINYINT(1) NOT NULL, is_sent TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `ehub_newsletter_subscriber` (id INT AUTO_INCREMENT NOT NULL, newsletter_id INT DEFAULT NULL, subscriber_id INT DEFAULT NULL, sent_at DATETIME NOT NULL, INDEX IDX_15AB8F3E22DB1917 (newsletter_id), INDEX IDX_15AB8F3E7808B1AD (subscriber_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `ehub_subscriber` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, email VARCHAR(150) NOT NULL, token VARCHAR(150) DEFAULT NULL, is_subscribed TINYINT(1) NOT NULL, has_received TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `ehub_temp_user` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B3E8D8C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `ehub_user` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, token VARCHAR(100) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `ehub_newsletter_subscriber` ADD CONSTRAINT FK_15AB8F3E22DB1917 FOREIGN KEY (newsletter_id) REFERENCES `ehub_newsletter` (id)');
        $this->addSql('ALTER TABLE `ehub_newsletter_subscriber` ADD CONSTRAINT FK_15AB8F3E7808B1AD FOREIGN KEY (subscriber_id) REFERENCES `ehub_subscriber` (id)');
        $this->addSql('ALTER TABLE `ehub_temp_user` ADD CONSTRAINT FK_B3E8D8C5A76ED395 FOREIGN KEY (user_id) REFERENCES `ehub_user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `ehub_newsletter_subscriber` DROP FOREIGN KEY FK_15AB8F3E22DB1917');
        $this->addSql('ALTER TABLE `ehub_newsletter_subscriber` DROP FOREIGN KEY FK_15AB8F3E7808B1AD');
        $this->addSql('ALTER TABLE `ehub_temp_user` DROP FOREIGN KEY FK_B3E8D8C5A76ED395');
        $this->addSql('DROP TABLE `ehub_newsletter`');
        $this->addSql('DROP TABLE `ehub_newsletter_subscriber`');
        $this->addSql('DROP TABLE `ehub_subscriber`');
        $this->addSql('DROP TABLE `ehub_temp_user`');
        $this->addSql('DROP TABLE `ehub_user`');
    }
}
