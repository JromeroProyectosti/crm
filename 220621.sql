ALTER TABLE lotes ADD orden INT NOT NULL, ADD is_utilizado TINYINT(1) NOT NULL;
ALTER TABLE lotes ADD is_asignado TINYINT(1) DEFAULT 0 NOT NULL;
CREATE TABLE usuario_lote (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, lote_id INT NOT NULL, INDEX IDX_CCE488FDB38439E (usuario_id), INDEX IDX_CCE488FB172197C (lote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE usuario_lote ADD CONSTRAINT FK_CCE488FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id);
ALTER TABLE usuario_lote ADD CONSTRAINT FK_CCE488FB172197C FOREIGN KEY (lote_id) REFERENCES lotes (id);
ALTER TABLE contrato ADD id_lote_id INT DEFAULT NULL;
ALTER TABLE contrato ADD CONSTRAINT FK_666965236FEFB00C FOREIGN KEY (id_lote_id) REFERENCES lotes (id);

INSERT INTO `lotes` (`id`, `empresa_id`, `nombre`, `estado`, `orden`, `is_utilizado`, `is_asignado`) VALUES
(1, 1, '1', 1, 1, 1, 0),
(2, 1, '2', 1, 2, 1, 0),
(3, 1, '3', 1, 3, 0, 0),
(4, 1, '4', 1, 4, 0, 0),
(5, 1, '5', 1, 5, 0, 0),
(6, 1, '6', 1, 6, 0, 0),
(7, 1, '7', 1, 7, 0, 0),
(8, 1, '8', 1, 8, 0, 0),
(9, 1, '9', 1, 9, 0, 0),
(10, 1, '10', 1, 10, 0, 0),
(11, 1, '11', 1, 11, 0, 0),
(12, 1, '12', 1, 12, 0, 0);

update contrato set id_lote_id=lote;

ALTER TABLE `contrato` DROP FOREIGN KEY `FK_666965236FEFB00C`; ALTER TABLE `contrato` ADD CONSTRAINT `FK_666965236FEFB00C` FOREIGN KEY (`id_lote_id`) REFERENCES `lotes`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION;