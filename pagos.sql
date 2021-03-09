INSERT INTO `modulo` (`id`, `nombre`, `ruta`, `nombre_alt`, `descripcion`) VALUES (NULL, 'pago', 'pago_index', 'Pagos', 'pago');
CREATE TABLE pago (id INT AUTO_INCREMENT NOT NULL, pago_tipo_id INT NOT NULL, pago_canal_id INT NOT NULL, usuario_registro_id INT NOT NULL, monto INT NOT NULL, boleta VARCHAR(255) NOT NULL, observacion LONGTEXT DEFAULT NULL, fecha_pago DATETIME NOT NULL, hora_pago TIME NOT NULL, fecha_registro DATETIME NOT NULL, INDEX IDX_F4DF5F3EC6690F67 (pago_tipo_id), INDEX IDX_F4DF5F3EFF66CCC7 (pago_canal_id), INDEX IDX_F4DF5F3E1EEFD20 (usuario_registro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE pago_canal (id INT AUTO_INCREMENT NOT NULL, empresa_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, estado TINYINT(1) DEFAULT NULL, orden INT DEFAULT NULL, INDEX IDX_23705BC1521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE pago_tipo (id INT AUTO_INCREMENT NOT NULL, empresa_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, orden INT DEFAULT NULL, estado TINYINT(1) DEFAULT NULL, INDEX IDX_567222FB521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3EC6690F67 FOREIGN KEY (pago_tipo_id) REFERENCES pago_tipo (id);
ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3EFF66CCC7 FOREIGN KEY (pago_canal_id) REFERENCES pago_canal (id);
ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3E1EEFD20 FOREIGN KEY (usuario_registro_id) REFERENCES usuario (id);
ALTER TABLE pago_canal ADD CONSTRAINT FK_23705BC1521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id);
ALTER TABLE pago_tipo ADD CONSTRAINT FK_567222FB521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id);
ALTER TABLE contrato ADD fecha_ultimo_pago DATE DEFAULT NULL;

ALTER TABLE contrato ADD is_finalizado TINYINT(1) DEFAULT NULL;

CREATE TABLE pago_cuotas (id INT AUTO_INCREMENT NOT NULL, pago_id INT NOT NULL, cuota_id INT NOT NULL, monto INT NOT NULL, INDEX IDX_75D1048763FB8380 (pago_id), INDEX IDX_75D104876A7CF079 (cuota_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE pago_cuotas ADD CONSTRAINT FK_75D1048763FB8380 FOREIGN KEY (pago_id) REFERENCES pago (id);
ALTER TABLE pago_cuotas ADD CONSTRAINT FK_75D104876A7CF079 FOREIGN KEY (cuota_id) REFERENCES cuota (id);

CREATE TABLE cuenta_corriente (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE pago ADD cuenta_corriente_id INT NOT NULL, ADD fecha_ingreso DATE NOT NULL, ADD ncomprobante VARCHAR(255) NOT NULL;
ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3E1FB75A3B FOREIGN KEY (cuenta_corriente_id) REFERENCES cuenta_corriente (id);
CREATE INDEX IDX_F4DF5F3E1FB75A3B ON pago (cuenta_corriente_id);
ALTER TABLE pago ADD comprobante LONGTEXT NOT NULL;

ALTER TABLE pago ADD usuario_anulacion_id INT DEFAULT NULL, ADD anulado TINYINT(1) DEFAULT NULL, ADD fecha_anulacion DATETIME DEFAULT NULL;
ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3EBAF036CE FOREIGN KEY (usuario_anulacion_id) REFERENCES usuario (id);
CREATE INDEX IDX_F4DF5F3EBAF036CE ON pago (usuario_anulacion_id);


ALTER TABLE `pago` CHANGE `boleta` `boleta` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `ncomprobante` `ncomprobante` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
INSERT INTO `usuario_tipo` (`id`, `nombre`, `orden`, `fijar`, `nombre_interno`, `menu_cabezera_id`, `empresa_id`) VALUES (NULL, 'Administrativo', NULL, '1', 'Administrativo', NULL, NULL);
INSERT INTO `usuario_tipo` (`id`, `nombre`, `orden`, `fijar`, `nombre_interno`, `menu_cabezera_id`, `empresa_id`) VALUES (NULL, 'Cobradores', NULL, '1', 'Cobradores', NULL, NULL);