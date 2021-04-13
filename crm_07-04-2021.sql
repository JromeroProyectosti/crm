INSERT INTO `modulo` (`id`, `nombre`, `ruta`, `nombre_alt`, `descripcion`) VALUES (NULL, 'terminos', 'terminos_index', 'Términos de contrato', 'COntratos terminados ');
ALTER TABLE cuota ADD is_multa TINYINT(1) DEFAULT NULL;
ALTER TABLE contrato ADD pdf_termino VARCHAR(255) DEFAULT NULL;

INSERT INTO `agenda_status` (`id`, `nombre`, `perfil`, `orden`, `icon`, `color`) VALUES
(12, 'Desconoce', 0, NULL, NULL, NULL),
(13, 'Desistió', 0, NULL, NULL, NULL),
(14, 'Reconsidera', 0, NULL, NULL, NULL),
(15, 'Ratifica Termino', 0, NULL, NULL, NULL);


ALTER TABLE configuracion ADD valor_multa INT DEFAULT NULL;
UPDATE `configuracion` SET `valor_multa` = '120000' WHERE `configuracion`.`id` = 1;

CREATE TABLE contrato_anexo (id INT AUTO_INCREMENT NOT NULL, contrato_id INT NOT NULL, fecha_creacion DATETIME NOT NULL, pdf VARCHAR(255) DEFAULT NULL, INDEX IDX_9DD8C30E70AE7BF1 (contrato_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE contrato_anexo ADD CONSTRAINT FK_9DD8C30E70AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id);
ALTER TABLE contrato_anexo ADD is_desiste TINYINT(1) NOT NULL;

ALTER TABLE cuota ADD anexo_id INT DEFAULT NULL;
ALTER TABLE cuota ADD CONSTRAINT FK_763CCB0FC9348664 FOREIGN KEY (anexo_id) REFERENCES contrato_anexo (id);
CREATE INDEX IDX_763CCB0FC9348664 ON cuota (anexo_id);

