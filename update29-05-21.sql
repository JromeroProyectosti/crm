ALTER TABLE contrato ADD fecha_termino DATETIME DEFAULT NULL;
ALTER TABLE contrato ADD vigencia INT DEFAULT NULL;
update `contrato` set vigencia=24 WHERE 1;