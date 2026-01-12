<?php
// db_config.php

// Configuración de base de datos usando variables de entorno
// Intentamos leer las variables de entorno (Docker/.env) si existen
$db_host     = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: null;
$db_name     = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: null;
$db_user     = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: null;
$db_password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: null;

// Detectamos entorno solo con ifs (sin funciones adicionales)
$host_check = strtolower($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '');
$addr_check = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
$app_env    = strtolower(getenv('APP_ENV') ?: '');

// Si falta cualquier credencial, asignamos por defecto según entorno
if (!$db_host || !$db_name || !$db_user || !$db_password) {
    // Consideramos localhost si el host contiene 'localhost', o la IP es loopback,
    // o si estamos en CLI y APP_ENV=development
    if (strpos($host_check, 'localhost') !== false || $addr_check === '127.0.0.1' || $addr_check === '::1' || (php_sapi_name() === 'cli' && $app_env === 'development')) {
        // Credenciales para desarrollo local / Docker
        $db_host     = $db_host     ?? 'database';       // servicio 'database' en docker-compose
        $db_name     = $db_name     ?? 'mi_aplicacion';
        $db_user     = $db_user     ?? 'developer';
        $db_password = $db_password ?? 'dev_password';
    } else {
        // Credenciales para InfinityFree (hosting remoto)
        $db_host     = $db_host     ?? 'sql100.infinityfree.com';
        $db_name     = $db_name     ?? 'if0_40888273_1234';
        $db_user     = $db_user     ?? 'if0_40888273';
        $db_password = $db_password ?? 'i34yQBL8DRdd';
    }
}

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