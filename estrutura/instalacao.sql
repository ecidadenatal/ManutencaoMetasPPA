create table plugins.orcprojativmanutencaometas (
	sequencial integer not null,
	orcprojativmetas integer not null,
	valor double precision not null
);

CREATE SEQUENCE plugins.orcprojativmanutencaometas_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;