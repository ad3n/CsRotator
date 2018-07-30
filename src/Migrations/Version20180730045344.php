<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180730045344 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE groups (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(77) NOT NULL, INDEX group_search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menus (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', parent_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(17) NOT NULL, name VARCHAR(27) NOT NULL, menu_order INT DEFAULT NULL, icon_class VARCHAR(27) DEFAULT NULL, route_name VARCHAR(77) DEFAULT NULL, UNIQUE INDEX UNIQ_727508CF77153098 (code), INDEX IDX_727508CF727ACA70 (parent_id), INDEX menu_search_idx (code, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', group_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(12) NOT NULL, kata_sandi VARCHAR(255) NOT NULL, INDEX IDX_1483A5E9FE54D947 (group_id), INDEX user_search_idx (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', group_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', menu_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', addable TINYINT(1) NOT NULL, editable TINYINT(1) NOT NULL, viewable TINYINT(1) NOT NULL, deletable TINYINT(1) NOT NULL, INDEX IDX_B63E2EC7FE54D947 (group_id), INDEX IDX_B63E2EC7CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menus ADD CONSTRAINT FK_727508CF727ACA70 FOREIGN KEY (parent_id) REFERENCES menus (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7CCD7E912 FOREIGN KEY (menu_id) REFERENCES menus (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9FE54D947');
        $this->addSql('ALTER TABLE roles DROP FOREIGN KEY FK_B63E2EC7FE54D947');
        $this->addSql('ALTER TABLE menus DROP FOREIGN KEY FK_727508CF727ACA70');
        $this->addSql('ALTER TABLE roles DROP FOREIGN KEY FK_B63E2EC7CCD7E912');
        $this->addSql('DROP TABLE groups');
        $this->addSql('DROP TABLE menus');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE roles');
    }
}
