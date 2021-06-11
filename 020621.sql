CREATE TABLE cobranza (id INT AUTO_INCREMENT NOT NULL, funcion_id INT DEFAULT NULL, respuesta_id INT DEFAULT NULL, cuota_id INT DEFAULT NULL, fecha_hora DATETIME NOT NULL, fecha_compromiso DATETIME DEFAULT NULL, observacion LONGTEXT DEFAULT NULL, is_nulo TINYINT(1) DEFAULT NULL, INDEX IDX_AE20EF3D8C185C36 (funcion_id), INDEX IDX_AE20EF3DD9BA57A2 (respuesta_id), INDEX IDX_AE20EF3D6A7CF079 (cuota_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE cobranza_funcion (id INT AUTO_INCREMENT NOT NULL, empresa_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_79CE3564521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE cobranza_respuesta (id INT AUTO_INCREMENT NOT NULL, empresa_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_B792E924521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE cobranza ADD CONSTRAINT FK_AE20EF3D8C185C36 FOREIGN KEY (funcion_id) REFERENCES cobranza_funcion (id);
ALTER TABLE cobranza ADD CONSTRAINT FK_AE20EF3DD9BA57A2 FOREIGN KEY (respuesta_id) REFERENCES cobranza_respuesta (id);
ALTER TABLE cobranza ADD CONSTRAINT FK_AE20EF3D6A7CF079 FOREIGN KEY (cuota_id) REFERENCES cuota (id);
ALTER TABLE cobranza_funcion ADD CONSTRAINT FK_79CE3564521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id);
ALTER TABLE cobranza_respuesta ADD CONSTRAINT FK_B792E924521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id);
ALTER TABLE cobranza_respuesta ADD is_fecha_compromiso TINYINT(1) DEFAULT NULL;
ALTER TABLE cobranza ADD usuario_registro_id INT NOT NULL, CHANGE funcion_id funcion_id INT DEFAULT NULL, CHANGE respuesta_id respuesta_id INT DEFAULT NULL, CHANGE cuota_id cuota_id INT DEFAULT NULL, CHANGE fecha_compromiso fecha_compromiso DATETIME DEFAULT NULL, CHANGE is_nulo is_nulo TINYINT(1) DEFAULT NULL;
ALTER TABLE cobranza ADD CONSTRAINT FK_AE20EF3D1EEFD20 FOREIGN KEY (usuario_registro_id) REFERENCES usuario (id);
CREATE INDEX IDX_AE20EF3D1EEFD20 ON cobranza (usuario_registro_id);

INSERT INTO `cobranza_funcion` (`id`, `empresa_id`, `nombre`) VALUES
(1, 1, 'Llamada 1'),
(2, 1, 'Llamada 2'),
(3, 1, 'Llamada 3'),
(4, 1, 'Llamada Extra'),
(5, 1, 'Envio Email'),
(6, 1, 'Envío SMS'),
(7, 1, 'Envío Wsp');

INSERT INTO `cobranza_respuesta` (`id`, `empresa_id`, `nombre`, `is_fecha_compromiso`) VALUES
(1, 1, 'Proceso', 0),
(2, 1, 'Compromiso', 1),
(3, 1, 'Inubicable', 0),
(4, 1, 'Recado', 0),
(5, 1, 'Envió Comprobante', 0),
(6, 1, 'Confirma Cita', 0),
(7, 1, 'Abonó', 0),
(8, 1, 'Firmo Anexo', 0),
(9, 1, 'Rebeldia', 0),
(10, 1, 'Desistirá', 0);
