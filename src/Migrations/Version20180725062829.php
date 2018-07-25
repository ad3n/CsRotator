<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180725062829 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE campaign_contact_visits (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', campaign_contact_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', visit_time DATETIME NOT NULL, INDEX IDX_64CBAAE420B605E2 (campaign_contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE campaign_contact_visits ADD CONSTRAINT FK_64CBAAE420B605E2 FOREIGN KEY (campaign_contact_id) REFERENCES campaign_contacts (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE campaign_contact_visits');
    }
}
