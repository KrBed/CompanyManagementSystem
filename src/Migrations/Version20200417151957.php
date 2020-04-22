<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417151957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pay_rate CHANGE rate_per_hour rate_per_hour NUMERIC(10, 2) DEFAULT NULL, CHANGE overtime_rate overtime_rate NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE position CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE shift ADD number_of_hours SMALLINT DEFAULT NULL, CHANGE note note VARCHAR(255) DEFAULT NULL, CHANGE overtime_duty_rooster overtime_duty_rooster TINYINT(1) DEFAULT NULL, CHANGE count_time_before_work count_time_before_work TINYINT(1) DEFAULT NULL, CHANGE count_time_after_work count_time_after_work TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE department_id department_id INT DEFAULT NULL, CHANGE position_id position_id INT DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pay_rate CHANGE rate_per_hour rate_per_hour NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE overtime_rate overtime_rate NUMERIC(10, 2) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE position CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE shift DROP number_of_hours, CHANGE note note VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE overtime_duty_rooster overtime_duty_rooster TINYINT(1) DEFAULT \'NULL\', CHANGE count_time_before_work count_time_before_work TINYINT(1) DEFAULT \'NULL\', CHANGE count_time_after_work count_time_after_work TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE department_id department_id INT DEFAULT NULL, CHANGE position_id position_id INT DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE deleted_at deleted_at DATETIME DEFAULT \'NULL\'');
    }
}
