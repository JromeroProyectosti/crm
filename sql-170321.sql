Cobranza 
-Colocar Numero de lote en contrato incrementar en 1 (del 1 al 12) LIsto
-crear en usuario cobrador, lotes a los que puede ver. Pendiente
-Pantalla Cobranza pendiente, colocar los pagos pendiente (semaforo amarillo y rojo). Listo


ALTER TABLE configuracion ADD lotes INT DEFAULT NULL;
ALTER TABLE contrato ADD lote INT DEFAULT NULL;
ALTER TABLE usuario ADD lotes JSON DEFAULT NULL;