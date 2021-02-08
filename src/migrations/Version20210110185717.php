<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210110185717 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loan_archive (id INT AUTO_INCREMENT NOT NULL, loan_id INT NOT NULL, UNIQUE INDEX UNIQ_FBB3D3E8CE73868F (loan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE loan_archive_product (loan_archive_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_CEA6C24B39CD3039 (loan_archive_id), INDEX IDX_CEA6C24B4584665A (product_id), PRIMARY KEY(loan_archive_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE loan_archive ADD CONSTRAINT FK_FBB3D3E8CE73868F FOREIGN KEY (loan_id) REFERENCES loan (id)');
        $this->addSql('ALTER TABLE loan_archive_product ADD CONSTRAINT FK_CEA6C24B39CD3039 FOREIGN KEY (loan_archive_id) REFERENCES loan_archive (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE loan_archive_product ADD CONSTRAINT FK_CEA6C24B4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan_archive_product DROP FOREIGN KEY FK_CEA6C24B39CD3039');
        $this->addSql('DROP TABLE loan_archive');
        $this->addSql('DROP TABLE loan_archive_product');
    }
}
