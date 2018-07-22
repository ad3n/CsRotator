<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180721032430 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contacts (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(77) NOT NULL, whats_app_number VARCHAR(17) NOT NULL, INDEX IDX_3340157319EB6921 (client_id), INDEX contact_search_idx (name, whats_app_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campaign_contacts (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', campaign_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', contact_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_130C30CF639F774 (campaign_id), INDEX IDX_130C30CE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campaigns (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', client_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(77) NOT NULL, slug VARCHAR(77) NOT NULL, facebook_pixel VARCHAR(27) NOT NULL, greeting_message VARCHAR(255) DEFAULT NULL, INDEX IDX_E373747019EB6921 (client_id), INDEX campaign_search_idx (name, slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(77) NOT NULL, INDEX client_search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_3340157319EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE campaign_contacts ADD CONSTRAINT FK_130C30CF639F774 FOREIGN KEY (campaign_id) REFERENCES campaigns (id)');
        $this->addSql('ALTER TABLE campaign_contacts ADD CONSTRAINT FK_130C30CE7A1254A FOREIGN KEY (contact_id) REFERENCES contacts (id)');
        $this->addSql('ALTER TABLE campaigns ADD CONSTRAINT FK_E373747019EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE campaign_contacts DROP FOREIGN KEY FK_130C30CE7A1254A');
        $this->addSql('ALTER TABLE campaign_contacts DROP FOREIGN KEY FK_130C30CF639F774');
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_3340157319EB6921');
        $this->addSql('ALTER TABLE campaigns DROP FOREIGN KEY FK_E373747019EB6921');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE campaign_contacts');
        $this->addSql('DROP TABLE campaigns');
        $this->addSql('DROP TABLE clients');
    }
}
