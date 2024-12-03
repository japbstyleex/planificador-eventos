-- Crear base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS armave;

-- Usar la base de datos
USE armave;

-- Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    correo_electronico VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar varios usuarios
INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, rol)
VALUES
    ('admin', 'admin@ejemplo.com', 'admin', 'admin'),
    ('joseperales', 'joseantonioperalesbayon@gmail.com', 'contrasenia1', 'usuario'),
    ('aroa', 'aroafcor@gmail.com', 'contrasenia2', 'usuario');

-- Crear una tabla adicional si fuera necesario (por ejemplo, para almacenar preferencias de usuario)
CREATE TABLE IF NOT EXISTS preferencias_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    preferencia VARCHAR(100),
    valor VARCHAR(100),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- Insertar algunas preferencias para los usuarios (ahora los usuarios deben existir)
INSERT INTO preferencias_usuarios (id, id_usuario, preferencia, valor)
VALUES
    (1, 'tema', 'oscuro'),
    (2, 'tema', 'claro'),
    (3, 'notificaciones', 'activadas'),
    (4, 'notificaciones', 'desactivadas');

-- Ver los usuarios
SELECT * FROM usuarios;
