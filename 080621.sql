
ALTER TABLE contrato ADD fecha_compromiso DATE DEFAULT NULL;
ALTER TABLE contrato ADD ultima_funcion VARCHAR(255) DEFAULT NULL, ADD q_mov INT DEFAULT NULL;
ALTER TABLE cobranza ADD fecha DATE DEFAULT NULL;