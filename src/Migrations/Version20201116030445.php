<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116030445 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contrato (id INT AUTO_INCREMENT NOT NULL, estado_civil_id INT DEFAULT NULL, situacion_laboral_id INT DEFAULT NULL, estrategia_juridica_id INT DEFAULT NULL, escritura_id INT DEFAULT NULL, agenda_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telefono VARCHAR(255) NOT NULL, ciudad VARCHAR(255) NOT NULL, rut VARCHAR(255) NOT NULL, direccion VARCHAR(255) NOT NULL, comuna VARCHAR(255) NOT NULL, titulo_contrato VARCHAR(255) DEFAULT NULL, monto_nivel_deuda NUMERIC(10, 0) DEFAULT NULL, monto_contrato NUMERIC(10, 0) DEFAULT NULL, cuotas INT DEFAULT NULL, valor_cuota NUMERIC(10, 0) DEFAULT NULL, interes NUMERIC(5, 2) DEFAULT NULL, dia_pago INT DEFAULT NULL, INDEX IDX_6669652375376D93 (estado_civil_id), INDEX IDX_666965238577D28 (situacion_laboral_id), INDEX IDX_6669652362144410 (estrategia_juridica_id), INDEX IDX_666965235D855194 (escritura_id), UNIQUE INDEX UNIQ_66696523EA67784A (agenda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE escritura (id INT AUTO_INCREMENT NOT NULL, empresa_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_2B6F7E72521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estado_civil (id INT AUTO_INCREMENT NOT NULL, empresa_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_F4222D84521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estrategia_juridica (id INT AUTO_INCREMENT NOT NULL, empresa_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_B649EC96521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE situacion_laboral (id INT AUTO_INCREMENT NOT NULL, empresa_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_7F28676521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_6669652375376D93 FOREIGN KEY (estado_civil_id) REFERENCES estado_civil (id)');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_666965238577D28 FOREIGN KEY (situacion_laboral_id) REFERENCES situacion_laboral (id)');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_6669652362144410 FOREIGN KEY (estrategia_juridica_id) REFERENCES estrategia_juridica (id)');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_666965235D855194 FOREIGN KEY (escritura_id) REFERENCES escritura (id)');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523EA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE escritura ADD CONSTRAINT FK_2B6F7E72521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE estado_civil ADD CONSTRAINT FK_F4222D84521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE estrategia_juridica ADD CONSTRAINT FK_B649EC96521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE situacion_laboral ADD CONSTRAINT FK_7F28676521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE usuario CHANGE categoria_id categoria_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE tipo_documento_id tipo_documento_id INT DEFAULT NULL, CHANGE empresa_actual empresa_actual INT DEFAULT NULL, CHANGE fecha_no_disponible fecha_no_disponible DATETIME DEFAULT NULL, CHANGE whatsapp whatsapp VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE rut rut VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL, CHANGE sexo sexo VARCHAR(255) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE accion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE gestionar_id gestionar_id INT DEFAULT NULL, CHANGE agendador_id agendador_id INT DEFAULT NULL, CHANGE sucursal_id sucursal_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE abogado_id abogado_id INT DEFAULT NULL, CHANGE nombre_cliente nombre_cliente VARCHAR(255) DEFAULT NULL, CHANGE email_cliente email_cliente VARCHAR(255) DEFAULT NULL, CHANGE telefono_cliente telefono_cliente VARCHAR(255) DEFAULT NULL, CHANGE ciudad_cliente ciudad_cliente VARCHAR(255) DEFAULT NULL, CHANGE fecha_carga fecha_carga DATETIME DEFAULT NULL, CHANGE fecha_asignado fecha_asignado DATETIME DEFAULT NULL, CHANGE monto monto NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id INT DEFAULT NULL, CHANGE leadgen_id leadgen_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE created_time created_time INT DEFAULT NULL, CHANGE campos campos JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL, CHANGE page_id page_id DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL, CHANGE logo_alt logo_alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE menu_cabezera CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE depende_de_id depende_de_id INT DEFAULT NULL, CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE modulo_id modulo_id INT DEFAULT NULL, CHANGE icono icono VARCHAR(255) DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE empresa_id empresa_id INT DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio_tipousuario CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sucursal CHANGE cuenta_id cuenta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_cuenta CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modulo CHANGE nombre_alt nombre_alt VARCHAR(255) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_666965235D855194');
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_6669652375376D93');
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_6669652362144410');
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_666965238577D28');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('DROP TABLE escritura');
        $this->addSql('DROP TABLE estado_civil');
        $this->addSql('DROP TABLE estrategia_juridica');
        $this->addSql('DROP TABLE situacion_laboral');
        $this->addSql('ALTER TABLE accion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE gestionar_id gestionar_id INT DEFAULT NULL, CHANGE agendador_id agendador_id INT DEFAULT NULL, CHANGE sucursal_id sucursal_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE abogado_id abogado_id INT DEFAULT NULL, CHANGE nombre_cliente nombre_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE email_cliente email_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono_cliente telefono_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE ciudad_cliente ciudad_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_carga fecha_carga DATETIME DEFAULT \'NULL\', CHANGE fecha_asignado fecha_asignado DATETIME DEFAULT \'NULL\', CHANGE monto monto NUMERIC(10, 0) DEFAULT \'NULL\'');
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
