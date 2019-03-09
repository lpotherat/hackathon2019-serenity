<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190309094243 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX uuid_aire_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__aire AS SELECT id, uuid, nom, lat, lgt FROM aire');
        $this->addSql('DROP TABLE aire');
        $this->addSql('CREATE TABLE aire (id INTEGER NOT NULL, uuid CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , nom VARCHAR(255) NOT NULL COLLATE BINARY, lat NUMERIC(10, 6) NOT NULL, lgt NUMERIC(10, 6) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO aire (id, uuid, nom, lat, lgt) SELECT id, uuid, nom, lat, lgt FROM __temp__aire');
        $this->addSql('DROP TABLE __temp__aire');
        $this->addSql('CREATE UNIQUE INDEX uuid_aire_idx ON aire (uuid)');
        $this->addSql('DROP INDEX uuid_tag_idx');
        $this->addSql('DROP INDEX IDX_389B78389BC6E01');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tag AS SELECT id, aire_id, uuid, data, numserie FROM tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('CREATE TABLE tag (id INTEGER NOT NULL, aire_id INTEGER NOT NULL, uuid CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , data BLOB NOT NULL, numserie VARCHAR(20) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_389B78389BC6E01 FOREIGN KEY (aire_id) REFERENCES aire (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tag (id, aire_id, uuid, data, numserie) SELECT id, aire_id, uuid, data, numserie FROM __temp__tag');
        $this->addSql('DROP TABLE __temp__tag');
        $this->addSql('CREATE UNIQUE INDEX uuid_tag_idx ON tag (uuid)');
        $this->addSql('CREATE INDEX IDX_389B78389BC6E01 ON tag (aire_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX uuid_aire_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__aire AS SELECT id, uuid, nom, lat, lgt FROM aire');
        $this->addSql('DROP TABLE aire');
        $this->addSql('CREATE TABLE aire (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid CHAR(36) NOT NULL --(DC2Type:guid)
        , nom VARCHAR(255) NOT NULL, lat NUMERIC(10, 6) NOT NULL, lgt NUMERIC(10, 6) NOT NULL)');
        $this->addSql('INSERT INTO aire (id, uuid, nom, lat, lgt) SELECT id, uuid, nom, lat, lgt FROM __temp__aire');
        $this->addSql('DROP TABLE __temp__aire');
        $this->addSql('CREATE UNIQUE INDEX uuid_aire_idx ON aire (uuid)');
        $this->addSql('DROP INDEX IDX_389B78389BC6E01');
        $this->addSql('DROP INDEX uuid_tag_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tag AS SELECT id, aire_id, uuid, data, numserie FROM tag');
        $this->addSql('DROP TABLE tag');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, aire_id INTEGER NOT NULL, uuid CHAR(36) NOT NULL --(DC2Type:guid)
        , data BLOB NOT NULL, numserie VARCHAR(20) NOT NULL)');
        $this->addSql('INSERT INTO tag (id, aire_id, uuid, data, numserie) SELECT id, aire_id, uuid, data, numserie FROM __temp__tag');
        $this->addSql('DROP TABLE __temp__tag');
        $this->addSql('CREATE INDEX IDX_389B78389BC6E01 ON tag (aire_id)');
        $this->addSql('CREATE UNIQUE INDEX uuid_tag_idx ON tag (uuid)');
    }
}
