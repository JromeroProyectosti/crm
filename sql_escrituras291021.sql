CREATE TABLE contrato_mee (id INT AUTO_INCREMENT NOT NULL, contrato_id INT NOT NULL, mee_id INT NOT NULL, INDEX IDX_3B028F3670AE7BF1 (contrato_id), INDEX IDX_3B028F36C1825E09 (mee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE cuenta_materia (id INT AUTO_INCREMENT NOT NULL, cuenta_id INT NOT NULL, materia_id INT NOT NULL, INDEX IDX_FCAF66119AEFF118 (cuenta_id), INDEX IDX_FCAF6611B54DBBCB (materia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE materia (id INT AUTO_INCREMENT NOT NULL, empresa_id INT NOT NULL, nombre VARCHAR(50) NOT NULL, INDEX IDX_6DF05284521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE materia_estrategia (id INT AUTO_INCREMENT NOT NULL, materia_id INT NOT NULL, estrategia_juridica_id INT NOT NULL, INDEX IDX_8A1D519EB54DBBCB (materia_id), INDEX IDX_8A1D519E62144410 (estrategia_juridica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE mee (id INT AUTO_INCREMENT NOT NULL, materia_estrategia_id INT NOT NULL, escritura_id INT NOT NULL, INDEX IDX_9A1C8B54516A508C (materia_estrategia_id), INDEX IDX_9A1C8B545D855194 (escritura_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE contrato_mee ADD CONSTRAINT FK_3B028F3670AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id);
ALTER TABLE contrato_mee ADD CONSTRAINT FK_3B028F36C1825E09 FOREIGN KEY (mee_id) REFERENCES mee (id);
ALTER TABLE cuenta_materia ADD CONSTRAINT FK_FCAF66119AEFF118 FOREIGN KEY (cuenta_id) REFERENCES cuenta (id);
ALTER TABLE cuenta_materia ADD CONSTRAINT FK_FCAF6611B54DBBCB FOREIGN KEY (materia_id) REFERENCES materia (id);
ALTER TABLE materia ADD CONSTRAINT FK_6DF05284521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id);
ALTER TABLE materia_estrategia ADD CONSTRAINT FK_8A1D519EB54DBBCB FOREIGN KEY (materia_id) REFERENCES materia (id);
ALTER TABLE materia_estrategia ADD CONSTRAINT FK_8A1D519E62144410 FOREIGN KEY (estrategia_juridica_id) REFERENCES estrategia_juridica (id);
ALTER TABLE mee ADD CONSTRAINT FK_9A1C8B54516A508C FOREIGN KEY (materia_estrategia_id) REFERENCES materia_estrategia (id);
ALTER TABLE mee ADD CONSTRAINT FK_9A1C8B545D855194 FOREIGN KEY (escritura_id) REFERENCES escritura (id);
ALTER TABLE materia_estrategia ADD estado TINYINT(1) NOT NULL;
ALTER TABLE contrato_mee ADD mees LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE mee_id mee_id INT DEFAULT NULL;
        
INSERT INTO `modulo` (`id`, `nombre`, `ruta`, `nombre_alt`, `descripcion`) VALUES (NULL, 'materia_index', 'materia_index', 'Materia', 'Materia');
INSERT INTO `modulo` (`id`, `nombre`, `ruta`, `nombre_alt`, `descripcion`) VALUES (NULL, 'estrategia_juridica_index', 'estrategia_juridica_index', 'Sub Materia', 'Sub Materias');
INSERT INTO `modulo` (`id`, `nombre`, `ruta`, `nombre_alt`, `descripcion`) VALUES (NULL, 'escritura', 'escritura_index', 'Escrituras', 'Escrituras');