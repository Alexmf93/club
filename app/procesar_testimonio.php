<?php
// filepath: c:\Users\AULA4-2DAW\Desktop\club\app\procesar_testimonio.php
require_once 'db_config.php';

function respond_json($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: testimonio.php');
    exit;
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_autor = isset($_POST['id_autor']) && $_POST['id_autor'] !== '' ? (int)$_POST['id_autor'] : null;
    $contenido = isset($_POST['contenido']) ? trim($_POST['contenido']) : '';

    if ($id_autor === null) throw new Exception('Debe seleccionar un autor (socio).');
    if ($contenido === '') throw new Exception('El testimonio no puede estar vacío.');
    if (strlen($contenido) < 10) throw new Exception('El testimonio debe tener al menos 10 caracteres.');

    // Verificar que el id_autor existe
    $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE id = :id");
    $stmtCheck->execute([':id' => $id_autor]);
    if ($stmtCheck->rowCount() === 0) {
        throw new Exception('El socio seleccionado no existe.');
    }

    // INSERT nuevo testimonio (la fecha se asigna automáticamente)
    $sql = "INSERT INTO testimonio (id_autor, contenido, fecha) VALUES (:id_autor, :contenido, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_autor' => $id_autor,
        ':contenido' => $contenido,
    ]);
    $resultId = (int)$pdo->lastInsertId();

    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || strpos($accept, 'application/json') !== false;

    if ($isAjax) {
        respond_json(['success' => true, 'id' => $resultId]);
    } else {
        header('Location: testimonio.php');
        exit;
    }

} catch (Exception $e) {
    $msg = $e->getMessage();
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || strpos($accept, 'application/json') !== false;

    if ($isAjax) {
        respond_json(['success' => false, 'error' => $msg], 400);
    } else {
        header('Location: testimonio.php?error=' . urlencode($msg));
        exit;
    }
}
?>