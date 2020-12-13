<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116014402 extends AbstractMigration
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
        $this->addSql('ALTER TABLE agenda CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE gestionar_id gestionar_id INT DEFAULT NULL, CHANGE agendador_id agendador_id INT DEFAULT NULL, CHANGE sucursal_id sucursal_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE abogado_id abogado_id INT DEFAULT NULL, CHANGE nombre_cliente nombre_cliente VARCHAR(255) DEFAULT NULL, CHANGE email_cliente email_cliente VARCHAR(255) DEFAULT NULL, CHANGE telefono_cliente telefono_cliente VARCHAR(255) DEFAULT NULL, CHANGE ciudad_cliente ciudad_cliente VARCHAR(255) DEFAULT NULL, CHANGE fecha_carga fecha_carga DATETIME DEFAULT NULL, CHANGE fecha_asignado fecha_asignado DATETIME DEFAULT NULL, CHANGE monto monto NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_status ADD perfil INT NOT NULL');
        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id INT DEFAULT NULL, CHANGE leadgen_id leadgen_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE created_time created_time INT DEFAULT NULL, CHANGE campos campos JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL, CHANGE page_id page_id DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL, CHANGE logo_alt logo_alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE depende_de_id depende_de_id INT DEFAULT NULL, CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE modulo_id modulo_id INT DEFAULT NULL, CHANGE icono icono VARCHAR(255) DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu_cabezera CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modulo CHANGE nombre_alt nombre_alt VARCHAR(255) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio_tipousuario CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sucursal CHANGE cuenta_id cuenta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE categoria_id categoria_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE tipo_documento_id tipo_documento_id INT DEFAULT NULL, CHANGE empresa_actual empresa_actual INT DEFAULT NULL, CHANGE fecha_no_disponible fecha_no_disponible DATETIME DEFAULT NULL, CHANGE whatsapp whatsapp VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE rut rut VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL, CHANGE sexo sexo VARCHAR(255) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_cuenta CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE empresa_id empresa_id INT DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE gestionar_id gestionar_id INT DEFAULT NULL, CHANGE agendador_id agendador_id INT DEFAULT NULL, CHANGE sucursal_id sucursal_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE abogado_id abogado_id INT DEFAULT NULL, CHANGE nombre_cliente nombre_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE email_cliente email_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono_cliente telefono_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE ciudad_cliente ciudad_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_carga fecha_carga DATETIME DEFAULT \'NULL\', CHANGE fecha_asignado fecha_asignado DATETIME DEFAULT \'NULL\', CHANGE monto monto NUMERIC(10, 0) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda_status DROP perfil');
        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id INT DEFAULT NULL, CHANGE leadgen_id leadgen_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE created_time created_time INT DEFAULT NULL, CHANGE campos campos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT \'NULL\', CHANGE page_id page_id DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT \'NULL\', CHANGE logo_alt logo_alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE menu CHANGE depende_de_id depende_de_id INT DEFAULT NULL, CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE modulo_id modulo_id INT DEFAULT NULL, CHANGE icono icono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu_cabezera CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modulo CHANGE nombre_alt nombre_alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE privilegio CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio_tipousuario CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sucursal CHANGE cuenta_id cuenta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE categoria_id categoria_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE tipo_documento_id tipo_documento_id INT DEFAULT NULL, CHANGE empresa_actual empresa_actual INT DEFAULT NULL, CHANGE fecha_no_disponible fecha_no_disponible DATETIME DEFAULT \'NULL\', CHANGE whatsapp whatsapp VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE rut rut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE sexo sexo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE usuario_cuenta CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE empresa_id empresa_id INT DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
