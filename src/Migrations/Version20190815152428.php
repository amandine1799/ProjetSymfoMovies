<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190815152428 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE actors CHANGE died died VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media CHANGE trailer trailer VARCHAR(500) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, media_id INT NOT NULL, users_id INT NOT NULL, title VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, review LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, date DATETIME NOT NULL, rating VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_794381C667B3B43D (users_id), INDEX IDX_794381C6EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C667B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE actors CHANGE died died VARCHAR(255) DEFAULT \'\'NULL\'\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE media CHANGE trailer trailer VARCHAR(500) DEFAULT \'\'NULL\'\' COLLATE utf8mb4_unicode_ci');
    }
}
