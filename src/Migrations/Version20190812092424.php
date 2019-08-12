<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190812092424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE review ADD users_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C667B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_794381C667B3B43D ON review (users_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C667B3B43D');
        $this->addSql('DROP INDEX IDX_794381C667B3B43D ON review');
        $this->addSql('ALTER TABLE review DROP users_id');
    }
}
