CREATE DATABASE IF NOT EXISTS almacenUsuarios; 

CREATE TABLE IF NOT EXISTS almacenUsuarios.usuarios (
  nombre VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  usuario VARCHAR(30) NOT NULL PRIMARY KEY,
  password VARCHAR(255)
); 
