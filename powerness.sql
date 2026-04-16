CREATE DATABASE powerness;

USE powerness;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100),
    password VARCHAR(100)
);

CREATE TABLE progreso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100),
    peso DECIMAL(5,2),
    estatura DECIMAL(4,2),
    fecha DATE
);