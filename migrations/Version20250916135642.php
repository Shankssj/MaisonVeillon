<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250916135642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY fk_photo_piece');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418AA4F4A13 FOREIGN KEY (id_piece) REFERENCES pieces (id_piece) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo RENAME INDEX fk_photo_piece TO IDX_14B78418AA4F4A13');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418AA4F4A13');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT fk_photo_piece FOREIGN KEY (id_piece) REFERENCES pieces (id_piece) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo RENAME INDEX idx_14b78418aa4f4a13 TO fk_photo_piece');
    }
}
