<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201102224550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id INT DEFAULT NULL, CHANGE leadgen_id leadgen_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE created_time created_time INT DEFAULT NULL, CHANGE campos campos JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL, CHANGE logo_alt logo_alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE modulo_id modulo_id INT DEFAULT NULL, CHANGE depende_de_id depende_de_id INT DEFAULT NULL, CHANGE icono icono VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE modulo CHANGE nombre_alt nombre_alt VARCHAR(255) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio ADD modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio ADD CONSTRAINT FK_D0E1EA5188C77C8D FOREIGN KEY (modulo_per_id) REFERENCES modulo_per (id)');
        $this->addSql('CREATE INDEX IDX_D0E1EA5188C77C8D ON privilegio (modulo_per_id)');
        $this->addSql('ALTER TABLE privilegio_tipousuario ADD modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio_tipousuario ADD CONSTRAINT FK_BE31D90E88C77C8D FOREIGN KEY (modulo_per_id) REFERENCES modulo_per (id)');
        $this->addSql('CREATE INDEX IDX_BE31D90E88C77C8D ON privilegio_tipousuario (modulo_per_id)');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE habilitar_rxp habilitar_rxp TINYINT(1) DEFAULT NULL, CHANGE monto_rxp monto_rxp INT DEFAULT NULL, CHANGE pendientes pendientes INT DEFAULT NULL, CHANGE empresa_actual empresa_actual INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_cuenta CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id INT DEFAULT NULL, CHANGE leadgen_id leadgen_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE created_time created_time INT DEFAULT NULL, CHANGE campos campos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT \'NULL\', CHANGE logo_alt logo_alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE menu CHANGE modulo_id modulo_id INT DEFAULT NULL, CHANGE depende_de_id depende_de_id INT DEFAULT NULL, CHANGE icono icono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE modulo CHANGE nombre_alt nombre_alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE privilegio DROP FOREIGN KEY FK_D0E1EA5188C77C8D');
        $this->addSql('DROP INDEX IDX_D0E1EA5188C77C8D ON privilegio');
        $this->addSql('ALTER TABLE privilegio DROP modulo_per_id');
        $this->addSql('ALTER TABLE privilegio_tipousuario DROP FOREIGN KEY FK_BE31D90E88C77C8D');
        $this->addSql('DROP INDEX IDX_BE31D90E88C77C8D ON privilegio_tipousuario');
        $this->addSql('ALTER TABLE privilegio_tipousuario DROP modulo_per_id');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE habilitar_rxp habilitar_rxp TINYINT(1) DEFAULT \'NULL\', CHANGE monto_rxp monto_rxp INT DEFAULT NULL, CHANGE pendientes pendientes INT DEFAULT NULL, CHANGE empresa_actual empresa_actual INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_cuenta CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
