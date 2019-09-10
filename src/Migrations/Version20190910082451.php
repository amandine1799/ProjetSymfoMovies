<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190910082451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_users (id INT AUTO_INCREMENT NOT NULL, media_id INT DEFAULT NULL, users_id INT DEFAULT NULL, wish_list TINYINT(1) NOT NULL, have_seen TINYINT(1) NOT NULL, rating INT DEFAULT NULL, INDEX IDX_B9A6158EA9FDD75 (media_id), INDEX IDX_B9A615867B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, media_id INT NOT NULL, users_id INT NOT NULL, content INT NOT NULL, INDEX IDX_49CA4E7DEA9FDD75 (media_id), INDEX IDX_49CA4E7D67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, media_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_794381C6EA9FDD75 (media_id), INDEX IDX_794381C6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actors (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, biography LONGTEXT NOT NULL, born VARCHAR(255) NOT NULL, died VARCHAR(255) DEFAULT NULL, image VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_roles (users_id INT NOT NULL, roles_id INT NOT NULL, INDEX IDX_51498A8E67B3B43D (users_id), INDEX IDX_51498A8E38C751C4 (roles_id), PRIMARY KEY(users_id, roles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, genres_id INT NOT NULL, title VARCHAR(1024) NOT NULL, synopsis LONGTEXT NOT NULL, released DATE NOT NULL, poster VARCHAR(2000) NOT NULL, trailer VARCHAR(500) DEFAULT NULL, duration INT NOT NULL, type INT NOT NULL, INDEX IDX_6A2CA10C6A3B2603 (genres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_actors (media_id INT NOT NULL, actors_id INT NOT NULL, INDEX IDX_63526A3BEA9FDD75 (media_id), INDEX IDX_63526A3B7168CF59 (actors_id), PRIMARY KEY(media_id, actors_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media_users ADD CONSTRAINT FK_B9A6158EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE media_users ADD CONSTRAINT FK_B9A615867B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8E67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8E38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C6A3B2603 FOREIGN KEY (genres_id) REFERENCES genres (id)');
        $this->addSql('ALTER TABLE media_actors ADD CONSTRAINT FK_63526A3BEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_actors ADD CONSTRAINT FK_63526A3B7168CF59 FOREIGN KEY (actors_id) REFERENCES actors (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C6A3B2603');
        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8E38C751C4');
        $this->addSql('ALTER TABLE media_actors DROP FOREIGN KEY FK_63526A3B7168CF59');
        $this->addSql('ALTER TABLE media_users DROP FOREIGN KEY FK_B9A615867B3B43D');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D67B3B43D');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8E67B3B43D');
        $this->addSql('ALTER TABLE media_users DROP FOREIGN KEY FK_B9A6158EA9FDD75');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DEA9FDD75');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6EA9FDD75');
        $this->addSql('ALTER TABLE media_actors DROP FOREIGN KEY FK_63526A3BEA9FDD75');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE media_users');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE actors');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_roles');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_actors');
    }
}
