<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210204918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, loaned_by_id INT NOT NULL, borrowed_by_id INT NOT NULL, loaned_at DATETIME NOT NULL, return_at DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_C5D30D0349CA1B72 (loaned_by_id), INDEX IDX_C5D30D0339759382 (borrowed_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0349CA1B72 FOREIGN KEY (loaned_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0339759382 FOREIGN KEY (borrowed_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product ADD loan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADCE73868F FOREIGN KEY (loan_id) REFERENCES loan (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADCE73868F ON product (loan_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADCE73868F');
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP INDEX IDX_D34A04ADCE73868F ON product');
        $this->addSql('ALTER TABLE product DROP loan_id');
    }
}
