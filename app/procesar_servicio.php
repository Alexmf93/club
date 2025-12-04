<?php
// filepath: c:\Users\AULA4-2DAW\Desktop\club\app\procesar_servicio.php
require_once 'db_config.php';

function respond_json($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: servicio.php');
    exit;
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
    $descripcion = isset($_POST['nombre2']) ? trim($_POST['nombre2']) : '';
    $duracion = isset($_POST['duracion']) && $_POST['duracion'] !== '' ? (int)$_POST['duracion'] : null;
    $precio_raw = isset($_POST['precio']) ? trim($_POST['precio']) : '';
    $precio = $precio_raw !== '' ? (float) str_replace(',', '.', $precio_raw) : null;

    if ($descripcion === '') throw new Exception('La descripción del servicio es obligatoria.');
    if ($duracion === null || $duracion <= 0) throw new Exception('La duración debe ser un entero positivo (minutos).');
    if ($precio === null || $precio < 0) throw new Exception('El precio debe ser un número positivo.');

    if ($id) {
        $sql = "UPDATE servicios SET descripcion = :descripcion, duracion = :duracion, precio = :precio WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':descripcion' => $descripcion,
            ':duracion' => $duracion,
            ':precio' => $precio,
            ':id' => $id,
        ]);
        $resultId = $id;
    } else {
        $sql = "INSERT INTO servicios (descripcion, duracion, precio) VALUES (:descripcion, :duracion, :precio)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':descripcion' => $descripcion,
            ':duracion' => $duracion,
            ':precio' => $precio,
        ]);
        $resultId = (int)$pdo->lastInsertId();
    }

    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || strpos($accept, 'application/json') !== false;

    if ($isAjax) {
        respond_json(['success' => true, 'id' => $resultId]);
    } else {
        header('Location: servicio.php?id=' . $resultId . '#admin-servicios');
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
        header('Location: servicio.php?error=' . urlencode($msg));
        exit;
    }
}