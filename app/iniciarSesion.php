<?php
// 1. Siempre iniciar sesión al principio
session_start();

// 2. Incluir la configuración de la base de datos
require_once 'db_config.php';

// --- NUEVA COMPROBACIÓN DE DEPURACIÓN ---
// Verificamos si la variable $pdo se creó correctamente
if (!isset($pdo) || !$pdo instanceof PDO) {
    // Si no existe o no es un objeto PDO, hay un problema grave.
    // Mostramos un error y detenemos la ejecución.
    die("Error: No se pudo establecer la conexión a la base de datos. Revisa el archivo 'db_config.php'.");
}
// --- FIN DE LA COMPROBACIÓN ---


// 3. Verificar que el formulario haya sido enviado por el método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// 4. Obtener los datos del formulario
 $usuario = trim($_POST['usuario'] ?? '');
 $password = $_POST['password'] ?? '';

// 5. Validación básica
if (empty($usuario) || empty($password)) {
    $_SESSION['error_login'] = "Por favor, introduce usuario y contraseña.";
    header('Location: login.php');
    exit;
}

try {
    // 6. Preparar la consulta SQL (Ahora estamos seguros de que $pdo existe)
    $sql = "SELECT id, nombre, rol, clave FROM usuarios WHERE nombre = :usuario";
    $stmt = $pdo->prepare($sql);

    // 7. Vincular y ejecutar
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();

    // 8. Obtener el resultado
    $user = $stmt->fetch();

    // 9. Verificar credenciales
    if ($user && password_verify($password, $user['clave'])) {
        
        // *** LOGIN CORRECTO ***
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];
        
        header('Location: paginaPrincipal.php');
        exit;
        
    } else {
        // *** LOGIN INCORRECTO ***
        $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
        header('Location: login.php');
        exit;
    }

} catch (PDOException $e) {
    // 10. Manejar errores de consulta
    error_log("Error de login en BD: " . $e->getMessage());
    $_SESSION['error_login'] = $e->getMessage();
    header('Location: login.php');
    exit;
}