<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260814123757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f529939813b3db11');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f529939819eb6921');
        $this->addSql('DROP INDEX idx_f529939819eb6921');
        $this->addSql('DROP INDEX idx_f529939813b3db11');
        $this->addSql('ALTER TABLE "order" ADD master VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD client VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" DROP master_id');
        $this->addSql('ALTER TABLE "order" DROP client_id');
        $this->addSql('ALTER TABLE "order" ALTER vehicle_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" ADD master_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" DROP master');
        $this->addSql('ALTER TABLE "order" DROP client');
        $this->addSql('ALTER TABLE "order" ALTER vehicle_id SET NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f529939813b3db11 FOREIGN KEY (master_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f529939819eb6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f529939819eb6921 ON "order" (client_id)');
        $this->addSql('CREATE INDEX idx_f529939813b3db11 ON "order" (master_id)');
    }
}
