<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200126142819 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F567B3B43D');
        $this->addSql('DROP INDEX IDX_462CE4F567B3B43D ON position');
        $this->addSql('ALTER TABLE position DROP users_id');
        $this->addSql('ALTER TABLE user ADD position_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DD842E46 ON user (position_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE position ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F567B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_462CE4F567B3B43D ON position (users_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DD842E46');
        $this->addSql('DROP INDEX IDX_8D93D649DD842E46 ON user');
        $this->addSql('ALTER TABLE user DROP position_id');
    }
}
