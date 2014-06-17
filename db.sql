# Creamos la base de datos y un usuario para ella

CREATE DATABASE gestion_centro;

USE gestion_centro;

# Creamos la tabla de usuarios
CREATE TABLE usuario (
    dni VARCHAR(9) PRIMARY KEY,
    apellido VARCHAR(50),
    tipo_usuario INTEGER(1)
);

# Creamos la tabla de asignaturas
CREATE TABLE asignatura (
    identificador INTEGER PRIMARY KEY,
    nombre VARCHAR(30)
);

# Creamos la tabla de notas
CREATE TABLE nota (
    alumno VARCHAR(9) REFERENCES usuario(dni),
    asignatura INTEGER REFERENCES asignatura(identificador),
    nota INTEGER,
    PRIMARY KEY (alumno, asignatura)
);

# Insertamos algunos usuarios de prueba
INSERT INTO usuario VALUES ('admin', 'admin', 0);
INSERT INTO usuario VALUES ('11111111A', 'lopez', 1);
INSERT INTO usuario VALUES ('22222222B', 'sanchez', 1);
INSERT INTO usuario VALUES ('33333333C', 'bruch', 1);
INSERT INTO usuario VALUES ('12345678X', 'garcia', 1);

# Algunas asignaturas
INSERT INTO asignatura VALUES (1, 'DAW_M07');
INSERT INTO asignatura VALUES (2, 'DAW_M03');
INSERT INTO asignatura VALUES (3, 'DAM_M05');
INSERT INTO asignatura VALUES (4, 'DAM_M06');
INSERT INTO asignatura VALUES (5, 'DAM_M08');

# Algunas notas
INSERT INTO nota VALUES ('11111111A', 1, 7);
INSERT INTO nota VALUES ('11111111A', 2, 6);
INSERT INTO nota VALUES ('22222222B', 1, 4);
INSERT INTO nota VALUES ('22222222B', 2, 3);
INSERT INTO nota VALUES ('33333333C', 2, 8);
INSERT INTO nota VALUES ('12345678X', 3, 9);
INSERT INTO nota VALUES ('12345678X', 4, 4);
INSERT INTO nota VALUES ('12345678X', 5, 5);
