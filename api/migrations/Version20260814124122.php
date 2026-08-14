<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260814124122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" ADD master_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" DROP master');
        $this->addSql('ALTER TABLE "order" DROP client');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F529939813B3DB11 FOREIGN KEY (master_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F529939819EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_F529939813B3DB11 ON "order" (master_id)');
        $this->addSql('CREATE INDEX IDX_F529939819EB6921 ON "order" (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F529939813B3DB11');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F529939819EB6921');
        $this->addSql('DROP INDEX IDX_F529939813B3DB11');
        $this->addSql('DROP INDEX IDX_F529939819EB6921');
        $this->addSql('ALTER TABLE "order" ADD master VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD client VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" DROP master_id');
        $this->addSql('ALTER TABLE "order" DROP client_id');
    }
}
