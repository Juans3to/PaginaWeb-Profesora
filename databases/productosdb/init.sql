CREATE DATABASE IF NOT EXISTS almacenProductos;

CREATE TABLE IF NOT EXISTS almacenProductos.productos (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  cantidad INT NOT NULL
);

-- Crear usuario con permisos globales para conexiones externas
CREATE USER IF NOT EXISTS 'juan'@'%' IDENTIFIED BY 'juan123';

GRANT ALL PRIVILEGES ON almacenProductos.* TO 'juan'@'%'
FLUSH PRIVILEGES;
