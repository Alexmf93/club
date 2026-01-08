<?php
// db_config.php

// Configuración de base de datos usando variables de entorno
 $db_host = $_ENV['DB_HOST'] ?? 'database';
 $db_name = $_ENV['DB_NAME'] ?? 'mi_aplicacion';
 $db_user = $_ENV['DB_USER'] ?? 'developer';
 $db_password = $_ENV['DB_PASSWORD'] ?? 'dev_password';

// Configuración para mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- CÓDIGO QUE FALTABA ---

// 1. Crear el Data Source Name (DSN)
 $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

// 2. Opciones para la conexión PDO
 $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los resultados como arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa prepared statements nativos
];

// 3. Crear la instancia de PDO (la conexión)
try {
    $pdo = new PDO($dsn, $db_user, $db_password, $options);
} catch (\PDOException $e) {
    // Si la conexión falla, muestra un error claro y detén el script.
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// --- FIN DEL CÓDIGO QUE FALTABA ---