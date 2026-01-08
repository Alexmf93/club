CREATE TABLE
IF NOT EXISTS usuarios
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE,
    edad INT,
    clave VARCHAR(255) NOT NULL,
    rol ENUM('socio','administrador', 'normal') NOT NULL DEFAULT 'socio',
    telefono VARCHAR(20) UNIQUE,
    foto VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS servicios
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    duracion INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS testimonio
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_autor INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_autor) REFERENCES usuarios(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS noticia
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255),
    fecha_publicacion DATETIME NOT NULL 
);

CREATE TABLE IF NOT EXISTS citas
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_socio INT NOT NULL,
    id_servicio INT NOT NULL,
    fecha_cita DATE NOT NULL,
    hora_cita TIME NOT NULL, 
    FOREIGN KEY (id_socio) REFERENCES usuarios(id) 
        ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id) 
        ON DELETE CASCADE
);

-- Inserciones para la tabla usuarios
INSERT INTO usuarios
    (id, nombre, edad, clave, rol, telefono, foto, fecha_registro)
VALUES
    (1, 'Juan García', NULL, '1234', 'administrador', '+34123456789', 'imagenes/socio_1764860475_9d25f083e080.jpg', '2025-11-25 16:24:05'),
    (2, 'María García', NULL, '1234', 'socio', '987654321', '\\imagenes\\maria.jpg', '2025-11-25 16:24:05'),
    (3, 'Carlos pepe', NULL, '1234', 'socio', '+34789456123', 'imagenes/carlos.jpg', '2025-11-25 16:24:05'),
    (4, 'Alejandro Buendía', NULL, '$2y$10$eDdlVacq0DV93PlSVO2jYefKmeGBT1SyNA2vSSPNBP7VbnGb4yMPS', 'socio', '+34666666666', 'imagenes/socio_1764861434_f6417e250c49.jpg', '2025-12-04 15:13:05'),
    (6, 'David Lara', NULL, '$2y$10$QyVUZkzr2cAI1/qOup8zzuwn8j4u6U9LgdAtxapPE6AC/J6NDuPBy', 'socio', '+34666555555', 'imagenes/socio_1764861698_6edc45117063.jpg', '2025-12-04 15:21:38');

-- Inserciones para la tabla servicios
INSERT INTO servicios (id, descripcion, duracion, precio) VALUES
    (1, 'Clase de spinning', 45, 12.50),
    (2, 'Sauna', 30, 4.00),
    (3, 'Solarium', 15, 7.00),
    (7, 'Futbol', 16, 88.00),
    (8, 'Baloncesto', 90, 56.00),
    (17, 'Rugby', 40, 60.00);

-- Inserciones para la tabla testimonio
INSERT INTO testimonio (id, id_autor, contenido, fecha) VALUES
    (1, 2, 'Excelente club, muy recomendado. Las clases de spinning son fantásticas.', '2025-11-20 10:30:00'),
    (2, 3, 'Me encanta el ambiente y la atención del personal. Muy satisfecho con mis entrenamientos.', '2025-11-22 14:15:00'),
    (3, 2, 'El sauna y solarium son de primera calidad. Definitivamente volvería.', '2025-11-24 16:45:00'),
    (4, 2, 'Estoy muy contento con el trabajo realizado por Alejandro Moya y con esta rutina me voy a poner muy fuerte.', '2025-12-04 18:16:27');

-- Inserciones para la tabla noticia
INSERT INTO noticia (id, titulo, contenido, imagen, fecha_publicacion) VALUES
    (1, 'Premio a la excelencia ', 'Se nos ha concedido una disticción por parte del patronato de deportes por la excelencia de nuestras instalaciones ', '/imagenes/premio.jpg', '2025-10-14 16:45:11'),
    (2, 'Nueva clase de yoga matutino', 'Iniciamos nuevas clases de yoga todos los lunes y miércoles a las 7:00 AM. Perfecto para comenzar el día con energía y flexibilidad.', '/imagenes/yoga.jpg', '2025-11-25 09:00:00'),
    (3, 'Mantenimiento del centro completado', 'Se ha finalizado la renovación de instalaciones. Los vestuarios y piscina han sido modernizados para mayor comodidad de nuestros socios.', '/imagenes/mantenimiento.jpg', '2025-11-27 14:30:00'),
    (4, 'Torneo de natación 2025', 'Inscripciones abiertas para el torneo de natación anual. Categorías disponibles para todas las edades. ¡No te lo pierdas!', '/imagenes/piscina.jpg', '2025-12-01 16:00:00'),
    (5, 'Nuevo curso intensivo de boxeo.', 'Lanzamos un programa intensivo de boxeo para todos los niveles. Las sesiones se enfocan en técnica, resistencia y acondicionamiento físico. Entrena con nuestros instructores certificados y mejora tu rendimiento en cada golpe.', '/imagenes/boxeo.jpg', '2025-11-21 18:40:30');

-- Inserciones para la tabla citas
INSERT INTO citas (id, id_socio, id_servicio, fecha_cita, hora_cita) VALUES
    (1, 2, 1, '2025-11-26', '08:00:00'),
    (2, 3, 2, '2025-11-26', '14:30:00'),
    (3, 2, 3, '2025-11-27', '16:00:00'),
    (4, 4, 7, '2025-12-11', '20:30:00');
