<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019205222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `ehub_user_setting` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, pagination_limit INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_CEC8F5EDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `ehub_user_setting` ADD CONSTRAINT FK_CEC8F5EDA76ED395 FOREIGN KEY (user_id) REFERENCES `ehub_user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `ehub_user_setting` DROP FOREIGN KEY FK_CEC8F5EDA76ED395');
        $this->addSql('DROP TABLE `ehub_user_setting`');
    }
}
