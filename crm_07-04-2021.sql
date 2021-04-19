
ALTER TABLE configuracion ADD lotes INT DEFAULT NULL;
ALTER TABLE contrato ADD lote INT DEFAULT NULL;
ALTER TABLE usuario ADD lotes JSON DEFAULT NULL;


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


INSERT INTO `modulo` (`id`, `nombre`, `ruta`, `nombre_alt`, `descripcion`) VALUES
(36, 'administrativo', 'administrativo_index', 'Usuario Administrativo', 'Usuario para Pagos y Cobranzza'),
(37, 'pago_finalizado', 'pago_finalizado', 'Pago Finalizado', 'Es una extension de pagos filtrando solos los contratos finalizados'),
(38, 'pago_resumen', 'pago_resumen', 'Resumen de Pagos', 'Modulo anclado al modulo Pago, los privilegios serán los de pagos'),
(39, 'cobradores', 'cobradores_index', 'Cobradores', 'Usuario de Cobranza'),
(40, 'cobranza', 'cobranza_index', 'Cobranza', 'Cobranza'),
(41, 'reporte_agendador', 'reporte_agendador', 'Reporte Agendadores', 'Reporte de Agendadores'),
(42, 'reporte_abogado', 'reporte_abogado', 'Reporte Abogados', 'Reporte de Abogados'),
(43, 'reporte', 'reporte_index', 'Reportes', 'Reportes y Efectividad'),
(44, 'comision', 'comision_index', 'Comisiones', 'Resumen y detalle de comisiones'),
(45, 'comision_agendador', 'comision_agendador', 'Comision Agendadores', 'Comision Agendadores'),
(46, 'comision_abogado', 'comision_abogado', 'Comision Abogados', 'Comisiones Abogados'),
(47, 'estadistico', 'estadistico_index', 'Estadísticos', 'Gráficos Estadísticos'),
(48, 'estadistico_marketing', 'estadistico_marketing', 'Estadísticos Marketing', 'Gráficos Estadísticos Marketing'),
(49, 'terminos', 'terminos_index', 'Términos de contrato', 'COntratos terminados '),
(50, 'desconoce', 'desconoce_index', 'Quiere desistir o desconoce', 'Quiere desistir o desconoce'),
(51, 'multas', 'multas_index', 'Multas', 'Multas');
