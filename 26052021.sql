
ALTER TABLE contrato ADD fecha_desiste DATETIME DEFAULT NULL, ADD fecha_pdf_anexo DATETIME DEFAULT NULL

desiste
select c.*,ao.* from contrato c, agenda a, agenda_observacion ao where c.agenda_id=a.id and a.id=ao.agenda_id and ao.status_id in (13,12);

termina
select c.*,ao.* from contrato c, agenda a, agenda_observacion ao where c.agenda_id=a.id and a.id=ao.agenda_id and ao.status_id =15;
pdfanexo
select c.*, ca.* from contrato c, contrato_anexo ca where c.id=ca.contrato_id;