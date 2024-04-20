<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420141221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, engine_id INT NOT NULL, seller_id INT NOT NULL, brand VARCHAR(25) NOT NULL, model VARCHAR(25) NOT NULL, body_type VARCHAR(10) NOT NULL, registration_expiration_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, price INT NOT NULL, INDEX IDX_773DE69DE78C9C0A (engine_id), INDEX IDX_773DE69D8DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication (id INT AUTO_INCREMENT NOT NULL, seller_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment LONGTEXT NOT NULL, INDEX IDX_F9AFB5EB8DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine (id INT AUTO_INCREMENT NOT NULL, fuel_type VARCHAR(20) NOT NULL, engine_displacement DOUBLE PRECISION NOT NULL, power_kw SMALLINT NOT NULL, power_hp SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seller (id INT AUTO_INCREMENT NOT NULL, location VARCHAR(25) NOT NULL, phone VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DE78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D8DE820D9 FOREIGN KEY (seller_id) REFERENCES seller (id)');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EB8DE820D9 FOREIGN KEY (seller_id) REFERENCES seller (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DE78C9C0A');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D8DE820D9');
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EB8DE820D9');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE communication');
        $this->addSql('DROP TABLE engine');
        $this->addSql('DROP TABLE seller');
    }
}
