<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505131838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EB8DE820D9');
        $this->addSql('DROP INDEX IDX_F9AFB5EB8DE820D9 ON communication');
        $this->addSql('ALTER TABLE communication CHANGE seller_id car_id INT NOT NULL');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EBC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_F9AFB5EBC3C6F69F ON communication (car_id)');
        $this->addSql('ALTER TABLE engine CHANGE engine_displacement engine_displacement INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EBC3C6F69F');
        $this->addSql('DROP INDEX IDX_F9AFB5EBC3C6F69F ON communication');
        $this->addSql('ALTER TABLE communication CHANGE car_id seller_id INT NOT NULL');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EB8DE820D9 FOREIGN KEY (seller_id) REFERENCES seller (id)');
        $this->addSql('CREATE INDEX IDX_F9AFB5EB8DE820D9 ON communication (seller_id)');
        $this->addSql('ALTER TABLE engine CHANGE engine_displacement engine_displacement DOUBLE PRECISION NOT NULL');
    }
}
