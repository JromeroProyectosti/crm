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
