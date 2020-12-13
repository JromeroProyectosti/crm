<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201031203734 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id INT DEFAULT NULL, CHANGE leadgen_id leadgen_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE created_time created_time INT DEFAULT NULL, CHANGE campos campos JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE habilitar_rxp habilitar_rxp TINYINT(1) DEFAULT NULL, CHANGE monto_rxp monto_rxp INT DEFAULT NULL, CHANGE pendientes pendientes INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_cuenta DROP FOREIGN KEY FK_CBD55CC69AEFF118');
        $this->addSql('ALTER TABLE usuario_cuenta DROP FOREIGN KEY FK_CBD55CC6DB38439E');
        $this->addSql('DROP INDEX IDX_CBD55CC69AEFF118 ON usuario_cuenta');
        $this->addSql('DROP INDEX IDX_CBD55CC6DB38439E ON usuario_cuenta');
        $this->addSql('ALTER TABLE usuario_cuenta ADD id INT AUTO_INCREMENT NOT NULL, ADD id_cuenta_id INT DEFAULT NULL, ADD id_usuario_id INT DEFAULT NULL, DROP usuario_id, DROP cuenta_id, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE usuario_cuenta ADD CONSTRAINT FK_CBD55CC61ADA4D3F FOREIGN KEY (id_cuenta_id) REFERENCES cuenta (id)');
        $this->addSql('ALTER TABLE usuario_cuenta ADD CONSTRAINT FK_CBD55CC67EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_CBD55CC61ADA4D3F ON usuario_cuenta (id_cuenta_id)');
        $this->addSql('CREATE INDEX IDX_CBD55CC67EB2C349 ON usuario_cuenta (id_usuario_id)');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id INT DEFAULT NULL, CHANGE leadgen_id leadgen_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE created_time created_time INT DEFAULT NULL, CHANGE campos campos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE habilitar_rxp habilitar_rxp TINYINT(1) DEFAULT \'NULL\', CHANGE monto_rxp monto_rxp INT DEFAULT NULL, CHANGE pendientes pendientes INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_cuenta MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE usuario_cuenta DROP FOREIGN KEY FK_CBD55CC61ADA4D3F');
        $this->addSql('ALTER TABLE usuario_cuenta DROP FOREIGN KEY FK_CBD55CC67EB2C349');
        $this->addSql('DROP INDEX IDX_CBD55CC61ADA4D3F ON usuario_cuenta');
        $this->addSql('DROP INDEX IDX_CBD55CC67EB2C349 ON usuario_cuenta');
        $this->addSql('ALTER TABLE usuario_cuenta DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE usuario_cuenta ADD usuario_id INT NOT NULL, ADD cuenta_id INT NOT NULL, DROP id, DROP id_cuenta_id, DROP id_usuario_id');
        $this->addSql('ALTER TABLE usuario_cuenta ADD CONSTRAINT FK_CBD55CC69AEFF118 FOREIGN KEY (cuenta_id) REFERENCES cuenta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_cuenta ADD CONSTRAINT FK_CBD55CC6DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_CBD55CC69AEFF118 ON usuario_cuenta (cuenta_id)');
        $this->addSql('CREATE INDEX IDX_CBD55CC6DB38439E ON usuario_cuenta (usuario_id)');
        $this->addSql('ALTER TABLE usuario_cuenta ADD PRIMARY KEY (usuario_id, cuenta_id)');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
