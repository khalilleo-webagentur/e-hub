<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508184705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE `ehub_newsletter` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(200) NOT NULL, content LONGTEXT NOT NULL, can_be_published TINYINT(1) NOT NULL, is_sent TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C1590CCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `ehub_newsletter_subscriber` (id INT AUTO_INCREMENT NOT NULL, newsletter_id INT DEFAULT NULL, subscriber_id INT DEFAULT NULL, sent_at DATETIME NOT NULL, INDEX IDX_15AB8F3E22DB1917 (newsletter_id), INDEX IDX_15AB8F3E7808B1AD (subscriber_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `ehub_subscriber` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(100) DEFAULT NULL, email VARCHAR(150) NOT NULL, token VARCHAR(150) DEFAULT NULL, is_subscribed TINYINT(1) NOT NULL, has_received TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_DF904E6DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `ehub_temp_user` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B3E8D8C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `ehub_user` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, token VARCHAR(100) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `ehub_user_setting` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, pagination_limit INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_CEC8F5EDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_newsletter` ADD CONSTRAINT FK_C1590CCA76ED395 FOREIGN KEY (user_id) REFERENCES `ehub_user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_newsletter_subscriber` ADD CONSTRAINT FK_15AB8F3E22DB1917 FOREIGN KEY (newsletter_id) REFERENCES `ehub_newsletter` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_newsletter_subscriber` ADD CONSTRAINT FK_15AB8F3E7808B1AD FOREIGN KEY (subscriber_id) REFERENCES `ehub_subscriber` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_subscriber` ADD CONSTRAINT FK_DF904E6DA76ED395 FOREIGN KEY (user_id) REFERENCES `ehub_user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_temp_user` ADD CONSTRAINT FK_B3E8D8C5A76ED395 FOREIGN KEY (user_id) REFERENCES `ehub_user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_user_setting` ADD CONSTRAINT FK_CEC8F5EDA76ED395 FOREIGN KEY (user_id) REFERENCES `ehub_user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_newsletter` DROP FOREIGN KEY FK_C1590CCA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_newsletter_subscriber` DROP FOREIGN KEY FK_15AB8F3E22DB1917
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_newsletter_subscriber` DROP FOREIGN KEY FK_15AB8F3E7808B1AD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_subscriber` DROP FOREIGN KEY FK_DF904E6DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_temp_user` DROP FOREIGN KEY FK_B3E8D8C5A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `ehub_user_setting` DROP FOREIGN KEY FK_CEC8F5EDA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `ehub_newsletter`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `ehub_newsletter_subscriber`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `ehub_subscriber`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `ehub_temp_user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `ehub_user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `ehub_user_setting`
        SQL);
    }
}
