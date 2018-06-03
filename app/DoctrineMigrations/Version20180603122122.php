<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Post and Category Schema
 */
final class Version20180603122122 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_64C19C12B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5A8A6C8D12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');

        //Create some sample content (Instead of fixtures)
        $this->addSql('INSERT INTO category (title) VALUES ("TEST1"), ("TEST2"), ("TEST3")');
        $this->addSql('INSERT INTO post (title, content, category_id, created_at) VALUES 
                          ("TEST1 TITLE", "TEST1 CONTENT", 1, current_timestamp()), 
                          ("TEST2 TITLE", "TEST2 CONTENT", 2, current_timestamp()), 
                          ("TEST3 TITLE", "TEST3 CONTENT", 3, current_timestamp()), 
                          ("TEST4 TITLE", "TEST4 CONTENT", 3, current_timestamp()), 
                          ("TEST5 TITLE", "TEST5 CONTENT", 3, current_timestamp())');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE post');
    }
}
