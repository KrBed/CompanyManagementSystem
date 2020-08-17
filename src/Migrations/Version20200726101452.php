<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200726101452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pay_rate (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, obtain_from DATETIME NOT NULL, rate_per_hour NUMERIC(10, 2) DEFAULT NULL, overtime_rate NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_8C75ED9EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shift (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, note VARCHAR(255) DEFAULT NULL, overtime_duty_rooster TINYINT(1) DEFAULT NULL, count_time_before_work TINYINT(1) DEFAULT NULL, count_time_after_work TINYINT(1) DEFAULT NULL, number_of_hours SMALLINT DEFAULT NULL, INDEX IDX_A50B3B45A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, position_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, plain_password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, note LONGTEXT DEFAULT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) DEFAULT NULL, town VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649AE80F5DF (department_id), INDEX IDX_8D93D649DD842E46 (position_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_status (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, status VARCHAR(255) NOT NULL, date DATETIME NOT NULL, send_by VARCHAR(255) NOT NULL, INDEX IDX_D0F9A33EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pay_rate ADD CONSTRAINT FK_8C75ED9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shift ADD CONSTRAINT FK_A50B3B45A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE work_status ADD CONSTRAINT FK_D0F9A33EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AE80F5DF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DD842E46');
        $this->addSql('ALTER TABLE pay_rate DROP FOREIGN KEY FK_8C75ED9EA76ED395');
        $this->addSql('ALTER TABLE shift DROP FOREIGN KEY FK_A50B3B45A76ED395');
        $this->addSql('ALTER TABLE work_status DROP FOREIGN KEY FK_D0F9A33EA76ED395');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE pay_rate');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE shift');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE work_status');
    }
}
