<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129213936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD amount INT NOT NULL');
        $this->addSql('ALTER TABLE price CHANGE type type ENUM(\'EXCL_TAX_BASE\', \'EXCL_TAX_TOTAL\', \'INCL_TAX_BASE\', \'INCL_TAX_TOTAL\', \'VAT_BASE_AMOUNT\') NOT NULL COMMENT \'(DC2Type:price_type_enum)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item DROP amount');
    }
}
