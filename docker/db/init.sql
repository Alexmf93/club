
CREATE TABLE
IF NOT EXISTS usuarios
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad INT,
    clave VARCHAR(255) NOT NULL,
    rol ENUM('socio', 'administrador') NOT NULL DEFAULT 'socio',
    telefono VARCHAR(20) UNIQUE,
    foto VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla servicios: id, descripcion, duracion (minutos), precio
CREATE TABLE IF NOT EXISTS servicios
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    duracion INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL
);

-- Tabla testimonio: id, id_autor (FK usuarios), contenido, fecha
CREATE TABLE IF NOT EXISTS testimonio
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_autor INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_autor) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla noticia: id, titulo, contenido, imagen, fecha_publicacion
CREATE TABLE IF NOT EXISTS noticia
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255),
    fecha_publicacion DATETIME NOT NULL
);

-- Tabla citas: id, id_socio (FK usuarios), id_servicio (FK servicios), fecha_cita, hora_cita
CREATE TABLE IF NOT EXISTS citas
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_socio INT NOT NULL,
    id_servicio INT NOT NULL,
    fecha_cita DATE NOT NULL,
    hora_cita TIME NOT NULL,
    FOREIGN KEY (id_socio) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id) ON DELETE CASCADE
);

-- Inserciones iniciales para la tabla servicios
INSERT INTO servicios (descripcion, duracion, precio) VALUES
    ('Clase de spinning', 45, 12.50),
    ('Sauna', 30, 5.00),
    ('Solarium', 15, 8.00);

INSERT INTO usuarios
    (nombre, clave, rol, telefono)
VALUES
    ('Juan Pérez', '1234', 'administrador', 123456789),
    ('María García', '1234', 'socio', 987654321),
    ('Carlos López', '1234', 'socio', 789456123);

-- Inserciones iniciales para la tabla testimonio
INSERT INTO testimonio (id_autor, contenido, fecha) VALUES
    (2, 'Excelente club, muy recomendado. Las clases de spinning son fantásticas.', '2025-11-20 10:30:00'),
    (3, 'Me encanta el ambiente y la atención del personal. Muy satisfecho con mis entrenamientos.', '2025-11-22 14:15:00'),
    (2, 'El sauna y solarium son de primera calidad. Definitivamente volvería.', '2025-11-24 16:45:00');

-- Inserciones iniciales para la tabla noticia
INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion) VALUES
    ('Nueva clase de yoga matutino', 'Iniciamos nuevas clases de yoga todos los lunes y miércoles a las 7:00 AM. Perfecto para comenzar el día con energía y flexibilidad.', '/imagenes/yoga.jpg', '2025-11-25 09:00:00'),
    ('Mantenimiento del centro completado', 'Se ha finalizado la renovación de instalaciones. Los vestuarios y piscina han sido modernizados para mayor comodidad de nuestros socios.', '/imagenes/mantenimiento.jpg', '2025-11-27 14:30:00'),
    ('Torneo de natación 2025', 'Inscripciones abiertas para el torneo de natación anual. Categorías disponibles para todas las edades. ¡No te lo pierdas!', '/imagenes/piscina.jpg', '2025-12-01 16:00:00');

-- Inserciones iniciales para la tabla citas
INSERT INTO citas (id_socio, id_servicio, fecha_cita, hora_cita) VALUES
    (2, 1, '2025-11-26', '08:00:00'),
    (3, 2, '2025-11-26', '14:30:00'),
    (2, 3, '2025-11-27', '16:00:00');


