<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial schema for Dashboard API';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $platform = $this->connection->getDatabasePlatform();
        
        if ($platform->getName() === 'sqlite') {
            // SQLite syntax
            $this->addSql('CREATE TABLE `user` (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, initials VARCHAR(50) DEFAULT NULL, color VARCHAR(50) DEFAULT NULL, role VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON `user` (email)');
            $this->addSql('CREATE TABLE campaign (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, platform VARCHAR(50) NOT NULL, title VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, progress INTEGER DEFAULT NULL, last_updated DATETIME NOT NULL, created_at DATETIME NOT NULL)');
            $this->addSql('CREATE TABLE campaign_collaborator (campaign_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(campaign_id, user_id))');
            $this->addSql('CREATE INDEX IDX_CAMPAIGN ON campaign_collaborator (campaign_id)');
            $this->addSql('CREATE INDEX IDX_USER ON campaign_collaborator (user_id)');
            $this->addSql('CREATE TABLE revenue (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
            $this->addSql('CREATE TABLE `order` (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, order_date DATE NOT NULL, status VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
            $this->addSql('CREATE TABLE subscription (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, subscription_date DATE NOT NULL, plan VARCHAR(50) DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
            $this->addSql('CREATE INDEX IDX_CAMPAIGN_FK ON campaign_collaborator (campaign_id)');
            $this->addSql('CREATE INDEX IDX_USER_FK ON campaign_collaborator (user_id)');
        } else {
            // MySQL/PostgreSQL syntax
            $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, initials VARCHAR(50) DEFAULT NULL, color VARCHAR(50) DEFAULT NULL, role VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE campaign (id INT AUTO_INCREMENT NOT NULL, platform VARCHAR(50) NOT NULL, title VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, progress INT DEFAULT NULL, last_updated DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE campaign_collaborator (campaign_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CAMPAIGN (campaign_id), INDEX IDX_USER (user_id), PRIMARY KEY(campaign_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE revenue (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, date DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, order_date DATE NOT NULL, status VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, subscription_date DATE NOT NULL, plan VARCHAR(50) DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
        if ($platform->getName() !== 'sqlite') {
            $this->addSql('ALTER TABLE campaign_collaborator ADD CONSTRAINT FK_CAMPAIGN FOREIGN KEY (campaign_id) REFERENCES campaign (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE campaign_collaborator ADD CONSTRAINT FK_USER FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        }
    }

    public function down(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform();
        
        if ($platform->getName() !== 'sqlite') {
            $this->addSql('ALTER TABLE campaign_collaborator DROP FOREIGN KEY FK_CAMPAIGN');
            $this->addSql('ALTER TABLE campaign_collaborator DROP FOREIGN KEY FK_USER');
        }
        
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE campaign');
        $this->addSql('DROP TABLE campaign_collaborator');
        $this->addSql('DROP TABLE revenue');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE subscription');
    }
}

