<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210119123504 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contrato_tramitador (id INT AUTO_INCREMENT NOT NULL, tramitadores_id INT NOT NULL, contrato_id INT NOT NULL, INDEX IDX_2A61EFE174A468AF (tramitadores_id), INDEX IDX_2A61EFE170AE7BF1 (contrato_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuota (id INT AUTO_INCREMENT NOT NULL, usuario_anulacion_id INT DEFAULT NULL, numero INT NOT NULL, fecha_pago DATE NOT NULL, monto INT NOT NULL, pagado INT DEFAULT NULL, anular TINYINT(1) DEFAULT NULL, fecha_anulacion DATETIME DEFAULT NULL, INDEX IDX_763CCB0FBAF036CE (usuario_anulacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE primera_cuota (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrato_tramitador ADD CONSTRAINT FK_2A61EFE174A468AF FOREIGN KEY (tramitadores_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE contrato_tramitador ADD CONSTRAINT FK_2A61EFE170AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id)');
        $this->addSql('ALTER TABLE cuota ADD CONSTRAINT FK_763CCB0FBAF036CE FOREIGN KEY (usuario_anulacion_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE accion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE gestionar_id gestionar_id INT DEFAULT NULL, CHANGE agendador_id agendador_id INT DEFAULT NULL, CHANGE sucursal_id sucursal_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE abogado_id abogado_id INT DEFAULT NULL, CHANGE reunion_id reunion_id INT DEFAULT NULL, CHANGE nombre_cliente nombre_cliente VARCHAR(255) DEFAULT NULL, CHANGE email_cliente email_cliente VARCHAR(255) DEFAULT NULL, CHANGE telefono_cliente telefono_cliente VARCHAR(255) DEFAULT NULL, CHANGE ciudad_cliente ciudad_cliente VARCHAR(255) DEFAULT NULL, CHANGE fecha_carga fecha_carga DATETIME DEFAULT NULL, CHANGE fecha_asignado fecha_asignado DATETIME DEFAULT NULL, CHANGE monto monto NUMERIC(10, 0) DEFAULT NULL, CHANGE rut_cliente rut_cliente VARCHAR(255) DEFAULT NULL, CHANGE telefono_recado_cliente telefono_recado_cliente VARCHAR(255) DEFAULT NULL, CHANGE fecha_contrato fecha_contrato DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda RENAME INDEX fk_2cedc8776bf700bd TO IDX_2CEDC8776BF700BD');
        $this->addSql('ALTER TABLE agenda_observacion CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_status CHANGE orden orden INT DEFAULT NULL, CHANGE icon icon VARCHAR(255) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id INT DEFAULT NULL, CHANGE leadgen_id leadgen_id INT DEFAULT NULL, CHANGE page_id page_id INT DEFAULT NULL, CHANGE created_time created_time INT DEFAULT NULL, CHANGE campos campos JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contrato ADD primera_cuota DOUBLE PRECISION DEFAULT NULL, ADD fecha_primera_cuota DATE DEFAULT NULL, ADD pdf VARCHAR(255) DEFAULT NULL, CHANGE estado_civil_id estado_civil_id INT DEFAULT NULL, CHANGE situacion_laboral_id situacion_laboral_id INT DEFAULT NULL, CHANGE estrategia_juridica_id estrategia_juridica_id INT DEFAULT NULL, CHANGE escritura_id escritura_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE sucursal_id sucursal_id INT DEFAULT NULL, CHANGE tramitador_id tramitador_id INT DEFAULT NULL, CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE pais_id pais_id INT DEFAULT NULL, CHANGE vehiculo_id vehiculo_id INT DEFAULT NULL, CHANGE vivienda_id vivienda_id INT DEFAULT NULL, CHANGE reunion_id reunion_id INT DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE ciudad ciudad VARCHAR(255) DEFAULT NULL, CHANGE rut rut VARCHAR(255) DEFAULT NULL, CHANGE titulo_contrato titulo_contrato VARCHAR(255) DEFAULT NULL, CHANGE monto_nivel_deuda monto_nivel_deuda NUMERIC(10, 0) DEFAULT NULL, CHANGE monto_contrato monto_contrato NUMERIC(10, 0) DEFAULT NULL, CHANGE cuotas cuotas INT DEFAULT NULL, CHANGE valor_cuota valor_cuota NUMERIC(10, 0) DEFAULT NULL, CHANGE interes interes NUMERIC(5, 2) DEFAULT NULL, CHANGE dia_pago dia_pago INT DEFAULT NULL, CHANGE fecha_creacion fecha_creacion DATETIME DEFAULT NULL, CHANGE clave_unica clave_unica VARCHAR(255) DEFAULT NULL, CHANGE telefono_recado telefono_recado VARCHAR(255) DEFAULT NULL, CHANGE fecha_primer_pago fecha_primer_pago DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE contrato RENAME INDEX fk_66696523279a5d5e TO IDX_66696523279A5D5E');
        $this->addSql('ALTER TABLE contrato_rol CHANGE juzgado_id juzgado_id INT DEFAULT NULL, CHANGE contrato_id contrato_id INT DEFAULT NULL, CHANGE nombre_rol nombre_rol VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contrato_vehiculo CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contrato_vivienda CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL, CHANGE page_id page_id DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT NULL, CHANGE logo_alt logo_alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE escritura CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE estado_civil CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE estrategia_juridica CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE importacion CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_carga_id usuario_carga_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE juzgado CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE juzgado RENAME INDEX empresa_id TO IDX_695F140E521E1991');
        $this->addSql('ALTER TABLE menu CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE modulo_id modulo_id INT DEFAULT NULL, CHANGE depende_de_id depende_de_id INT DEFAULT NULL, CHANGE icono icono VARCHAR(255) DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93A53F971C FOREIGN KEY (depende_de_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_cabezera CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modulo CHANGE nombre_alt nombre_alt VARCHAR(255) DEFAULT NULL, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pais CHANGE empresa_id empresa_id INT DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio_tipousuario CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reunion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE situacion_laboral CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sucursal CHANGE cuenta_id cuenta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE categoria_id categoria_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE nombre nombre VARCHAR(100) NOT NULL, CHANGE empresa_actual empresa_actual INT DEFAULT NULL, CHANGE fecha_no_disponible fecha_no_disponible DATETIME DEFAULT NULL, CHANGE whatsapp whatsapp VARCHAR(255) DEFAULT NULL, CHANGE telefono telefono VARCHAR(255) DEFAULT NULL, CHANGE rut rut VARCHAR(255) DEFAULT NULL, CHANGE direccion direccion VARCHAR(255) DEFAULT NULL, CHANGE sexo sexo VARCHAR(255) DEFAULT NULL, CHANGE tipo_documento_id tipo_documento_id INT DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL, CHANGE password_ant password_ant VARCHAR(255) DEFAULT NULL, CHANGE lunes lunes TINYINT(1) DEFAULT NULL, CHANGE lunes_start lunes_start TIME DEFAULT NULL, CHANGE lunes_end lunes_end TIME DEFAULT NULL, CHANGE martes martes TINYINT(1) DEFAULT NULL, CHANGE martes_start martes_start TIME DEFAULT NULL, CHANGE martes_end martes_end TIME DEFAULT NULL, CHANGE miercoles miercoles TINYINT(1) DEFAULT NULL, CHANGE miercoles_start miercoles_start TIME DEFAULT NULL, CHANGE miercoles_end miercoles_end TIME DEFAULT NULL, CHANGE jueves jueves TINYINT(1) DEFAULT NULL, CHANGE jueves_start jueves_start TIME DEFAULT NULL, CHANGE jueves_end jueves_end TIME DEFAULT NULL, CHANGE viernes viernes TINYINT(1) DEFAULT NULL, CHANGE viernes_start viernes_start TIME DEFAULT NULL, CHANGE viernes_end viernes_end TIME DEFAULT NULL, CHANGE sabado sabado TINYINT(1) DEFAULT NULL, CHANGE sabado_start sabado_start TIME DEFAULT NULL, CHANGE sabado_end sabado_end TIME DEFAULT NULL, CHANGE domingo domingo TINYINT(1) DEFAULT NULL, CHANGE domingo_start domingo_start TIME DEFAULT NULL, CHANGE domingo_end domingo_end TIME DEFAULT NULL, CHANGE sobrecupo sobrecupo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DF6939175 FOREIGN KEY (tipo_documento_id) REFERENCES usuario_tipo_documento (id)');
        $this->addSql('CREATE INDEX IDX_2265B05DF6939175 ON usuario (tipo_documento_id)');
        $this->addSql('ALTER TABLE usuario_cuenta CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_no_disponible CHANGE usuario_id usuario_id INT DEFAULT NULL, CHANGE fecha_inicio fecha_inicio DATE DEFAULT NULL, CHANGE fecha_fin fecha_fin DATE DEFAULT NULL, CHANGE anio anio INT DEFAULT NULL, CHANGE mes mes INT DEFAULT NULL, CHANGE dia dia INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE empresa_id empresa_id INT DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_usuariocategoria CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_usuariocategoria RENAME INDEX usuario_id TO IDX_892F60F1DB38439E');
        $this->addSql('ALTER TABLE usuario_usuariocategoria RENAME INDEX cuenta_id TO IDX_892F60F19AEFF118');
        $this->addSql('ALTER TABLE usuario_usuariocategoria RENAME INDEX agenda_id TO IDX_892F60F1EA67784A');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contrato_tramitador');
        $this->addSql('DROP TABLE cuota');
        $this->addSql('DROP TABLE primera_cuota');
        $this->addSql('ALTER TABLE accion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE gestionar_id gestionar_id INT DEFAULT NULL, CHANGE agendador_id agendador_id INT DEFAULT NULL, CHANGE sucursal_id sucursal_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE abogado_id abogado_id INT DEFAULT NULL, CHANGE reunion_id reunion_id INT DEFAULT NULL, CHANGE nombre_cliente nombre_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE email_cliente email_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono_cliente telefono_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE ciudad_cliente ciudad_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_carga fecha_carga DATETIME DEFAULT \'NULL\', CHANGE fecha_asignado fecha_asignado DATETIME DEFAULT \'NULL\', CHANGE monto monto NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE rut_cliente rut_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono_recado_cliente telefono_recado_cliente VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_contrato fecha_contrato DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda RENAME INDEX idx_2cedc8776bf700bd TO FK_2CEDC8776BF700BD');
        $this->addSql('ALTER TABLE agenda_observacion CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_status CHANGE orden orden INT DEFAULT NULL, CHANGE icon icon VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cliente_potencial CHANGE form_id form_id DOUBLE PRECISION DEFAULT \'NULL\', CHANGE leadgen_id leadgen_id DOUBLE PRECISION DEFAULT \'NULL\', CHANGE page_id page_id DOUBLE PRECISION DEFAULT \'NULL\', CHANGE created_time created_time DOUBLE PRECISION DEFAULT \'NULL\', CHANGE campos campos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE configuracion CHANGE host host VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE contrato DROP primera_cuota, DROP fecha_primera_cuota, DROP pdf, CHANGE estado_civil_id estado_civil_id INT DEFAULT NULL, CHANGE situacion_laboral_id situacion_laboral_id INT DEFAULT NULL, CHANGE estrategia_juridica_id estrategia_juridica_id INT DEFAULT NULL, CHANGE escritura_id escritura_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE sucursal_id sucursal_id INT DEFAULT NULL, CHANGE tramitador_id tramitador_id INT DEFAULT NULL, CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE pais_id pais_id INT DEFAULT NULL, CHANGE vehiculo_id vehiculo_id INT DEFAULT NULL, CHANGE vivienda_id vivienda_id INT DEFAULT NULL, CHANGE reunion_id reunion_id INT DEFAULT NULL, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE ciudad ciudad VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE rut rut VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE titulo_contrato titulo_contrato VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE monto_nivel_deuda monto_nivel_deuda NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE monto_contrato monto_contrato NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE cuotas cuotas INT DEFAULT NULL, CHANGE valor_cuota valor_cuota NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE interes interes NUMERIC(5, 2) DEFAULT \'NULL\', CHANGE dia_pago dia_pago INT DEFAULT NULL, CHANGE fecha_creacion fecha_creacion DATETIME DEFAULT \'NULL\', CHANGE clave_unica clave_unica VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono_recado telefono_recado VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_primer_pago fecha_primer_pago DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE contrato RENAME INDEX idx_66696523279a5d5e TO FK_66696523279A5D5E');
        $this->addSql('ALTER TABLE contrato_rol CHANGE juzgado_id juzgado_id INT DEFAULT NULL, CHANGE contrato_id contrato_id INT DEFAULT NULL, CHANGE nombre_rol nombre_rol VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE contrato_vehiculo CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contrato_vivienda CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cuenta CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT \'NULL\', CHANGE page_id page_id DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE empresa CHANGE rol rol VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE fecha_ultimamodificacion fecha_ultimamodificacion DATETIME DEFAULT \'NULL\', CHANGE logo_alt logo_alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE escritura CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE estado_civil CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE estrategia_juridica CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE importacion CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_carga_id usuario_carga_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE juzgado CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE juzgado RENAME INDEX idx_695f140e521e1991 TO empresa_id');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93A53F971C');
        $this->addSql('ALTER TABLE menu CHANGE depende_de_id depende_de_id INT DEFAULT NULL, CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE modulo_id modulo_id INT DEFAULT NULL, CHANGE icono icono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu_cabezera CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modulo CHANGE nombre_alt nombre_alt VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pais CHANGE empresa_id empresa_id INT DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE privilegio_tipousuario CHANGE modulo_per_id modulo_per_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reunion CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE situacion_laboral CHANGE empresa_id empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sucursal CHANGE cuenta_id cuenta_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05DF6939175');
        $this->addSql('DROP INDEX IDX_2265B05DF6939175 ON usuario');
        $this->addSql('ALTER TABLE usuario CHANGE categoria_id categoria_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL, CHANGE tipo_documento_id tipo_documento_id INT DEFAULT NULL, CHANGE nombre nombre VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE empresa_actual empresa_actual INT DEFAULT NULL, CHANGE fecha_no_disponible fecha_no_disponible DATETIME DEFAULT \'NULL\', CHANGE whatsapp whatsapp VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telefono telefono VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE rut rut VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE direccion direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE sexo sexo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE password_ant password_ant VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lunes lunes TINYINT(1) DEFAULT \'NULL\', CHANGE lunes_start lunes_start TIME DEFAULT \'NULL\', CHANGE lunes_end lunes_end TIME DEFAULT \'NULL\', CHANGE martes martes TINYINT(1) DEFAULT \'NULL\', CHANGE martes_start martes_start TIME DEFAULT \'NULL\', CHANGE martes_end martes_end TIME DEFAULT \'NULL\', CHANGE miercoles miercoles TINYINT(1) DEFAULT \'NULL\', CHANGE miercoles_start miercoles_start TIME DEFAULT \'NULL\', CHANGE miercoles_end miercoles_end TIME DEFAULT \'NULL\', CHANGE jueves jueves TINYINT(1) DEFAULT \'NULL\', CHANGE jueves_start jueves_start TIME DEFAULT \'NULL\', CHANGE jueves_end jueves_end TIME DEFAULT \'NULL\', CHANGE viernes viernes TINYINT(1) DEFAULT \'NULL\', CHANGE viernes_start viernes_start TIME DEFAULT \'NULL\', CHANGE viernes_end viernes_end TIME DEFAULT \'NULL\', CHANGE sabado sabado TINYINT(1) DEFAULT \'NULL\', CHANGE sabado_start sabado_start TIME DEFAULT \'NULL\', CHANGE sabado_end sabado_end TIME DEFAULT \'NULL\', CHANGE domingo domingo TINYINT(1) DEFAULT \'NULL\', CHANGE domingo_start domingo_start TIME DEFAULT \'NULL\', CHANGE domingo_end domingo_end TIME DEFAULT \'NULL\', CHANGE sobrecupo sobrecupo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_cuenta CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE usuario_id usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_no_disponible CHANGE usuario_id usuario_id INT DEFAULT NULL, CHANGE fecha_inicio fecha_inicio DATE DEFAULT \'NULL\', CHANGE fecha_fin fecha_fin DATE DEFAULT \'NULL\', CHANGE anio anio INT DEFAULT NULL, CHANGE mes mes INT DEFAULT NULL, CHANGE dia dia INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_tipo CHANGE menu_cabezera_id menu_cabezera_id INT DEFAULT NULL, CHANGE empresa_id empresa_id INT DEFAULT NULL, CHANGE orden orden INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_usuariocategoria CHANGE cuenta_id cuenta_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_usuariocategoria RENAME INDEX idx_892f60f1ea67784a TO agenda_id');
        $this->addSql('ALTER TABLE usuario_usuariocategoria RENAME INDEX idx_892f60f1db38439e TO usuario_id');
        $this->addSql('ALTER TABLE usuario_usuariocategoria RENAME INDEX idx_892f60f19aeff118 TO cuenta_id');
        $this->addSql('ALTER TABLE webhook CHANGE verify_token verify_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}