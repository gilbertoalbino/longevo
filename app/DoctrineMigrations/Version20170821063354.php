<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170821063354 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE chamados_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE clientes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pedidos_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE chamados (id INT NOT NULL, id_cliente INT DEFAULT NULL, id_pedido INT DEFAULT NULL, email VARCHAR(255) NOT NULL, titulo VARCHAR(255) NOT NULL, observacao TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C638B1C42A813255 ON chamados (id_cliente)');
        $this->addSql('CREATE INDEX IDX_C638B1C4E2DBA323 ON chamados (id_pedido)');
        $this->addSql('CREATE TABLE clientes (id INT NOT NULL, nome VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pedidos (id INT NOT NULL, id_cliente INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6716CCAA2A813255 ON pedidos (id_cliente)');
        $this->addSql('ALTER TABLE chamados ADD CONSTRAINT FK_C638B1C42A813255 FOREIGN KEY (id_cliente) REFERENCES clientes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chamados ADD CONSTRAINT FK_C638B1C4E2DBA323 FOREIGN KEY (id_pedido) REFERENCES pedidos (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA2A813255 FOREIGN KEY (id_cliente) REFERENCES clientes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chamados DROP CONSTRAINT FK_C638B1C42A813255');
        $this->addSql('ALTER TABLE pedidos DROP CONSTRAINT FK_6716CCAA2A813255');
        $this->addSql('ALTER TABLE chamados DROP CONSTRAINT FK_C638B1C4E2DBA323');
        $this->addSql('DROP SEQUENCE chamados_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE clientes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pedidos_id_seq CASCADE');
        $this->addSql('DROP TABLE chamados');
        $this->addSql('DROP TABLE clientes');
        $this->addSql('DROP TABLE pedidos');
    }
}
