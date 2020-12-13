<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201031144943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cliente_potencial (id INT AUTO_INCREMENT NOT NULL, form_id INT DEFAULT NULL, leadgen_id INT DEFAULT NULL, page_id INT DEFAULT NULL, created_time INT DEFAULT NULL, campos JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE configuracion (id INT AUTO_INCREMENT NOT NULL, dia_fondo_fijo INT NOT NULL, host VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuenta (id INT AUTO_INCREMENT NOT NULL, empresa_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, fecha_creacion DATETIME NOT NULL, fecha_ultimamodificacion DATETIME DEFAULT NULL, INDEX IDX_31C7BFCF521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuentas (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, rol VARCHAR(255) DEFAULT NULL, rut VARCHAR(20) NOT NULL, logo VARCHAR(255) DEFAULT NULL, fecha_ingreso DATETIME NOT NULL, fecha_ultimamodificacion DATETIME DEFAULT NULL, fecha_vigencia DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, usuario_tipo_id INT NOT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(20) NOT NULL, estado TINYINT(1) NOT NULL, fecha_activacion DATETIME NOT NULL, habilitar_rxp TINYINT(1) DEFAULT NULL, monto_rxp INT DEFAULT NULL, correo VARCHAR(255) NOT NULL, token LONGTEXT DEFAULT NULL, pendientes INT DEFAULT NULL, UNIQUE INDEX UNIQ_2265B05DF85E0677 (username), INDEX IDX_2265B05DD001C42B (usuario_tipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario_cuenta (usuario_id INT NOT NULL, cuenta_id INT NOT NULL, INDEX IDX_CBD55CC6DB38439E (usuario_id), INDEX IDX_CBD55CC69AEFF118 (cuenta_id), PRIMARY KEY(usuario_id, cuenta_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario_tipo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(20) NOT NULL, orden INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE webhook (id INT AUTO_INCREMENT NOT NULL, verify_token VARCHAR(255) DEFAULT NULL, token VARCHAR(255) NOT NULL, user_token LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cuenta ADD CONSTRAINT FK_31C7BFCF521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DD001C42B FOREIGN KEY (usuario_tipo_id) REFERENCES usuario_tipo (id)');
        $this->addSql('ALTER TABLE usuario_cuenta ADD CONSTRAINT FK_CBD55CC6DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_cuenta ADD CONSTRAINT FK_CBD55CC69AEFF118 FOREIGN KEY (cuenta_id) REFERENCES cuenta (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE usuario_cuenta DROP FOREIGN KEY FK_CBD55CC69AEFF118');
        $this->addSql('ALTER TABLE cuenta DROP FOREIGN KEY FK_31C7BFCF521E1991');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE usuario_cuenta DROP FOREIGN KEY FK_CBD55CC6DB38439E');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05DD001C42B');
        $this->addSql('DROP TABLE cliente_potencial');
        $this->addSql('DROP TABLE configuracion');
        $this->addSql('DROP TABLE cuenta');
        $this->addSql('DROP TABLE cuentas');
        $this->addSql('DROP TABLE empresa');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE usuario_cuenta');
        $this->addSql('DROP TABLE usuario_tipo');
        $this->addSql('DROP TABLE webhook');
    }
}
