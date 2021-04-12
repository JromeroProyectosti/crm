INSERT INTO `modulo` (`id`, `nombre`, `ruta`, `nombre_alt`, `descripcion`) VALUES (NULL, 'terminos', 'terminos_index', 'Términos de contrato', 'COntratos terminados ');
ALTER TABLE cuota ADD is_multa TINYINT(1) DEFAULT NULL;
ALTER TABLE contrato ADD pdf_termino VARCHAR(255) DEFAULT NULL;

INSERT INTO `agenda_status` (`id`, `nombre`, `perfil`, `orden`, `icon`, `color`) VALUES
(12, 'Desconoce', 0, NULL, NULL, NULL),
(13, 'Desistió', 0, NULL, NULL, NULL),
(14, 'Reconsidera', 0, NULL, NULL, NULL),
(15, 'Ratifica Termino', 0, NULL, NULL, NULL);
