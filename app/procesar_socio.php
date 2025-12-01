<?php
require_once 'db_config.php';

function respond_json($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: socio.php');
    exit;
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nombre === '') {
        throw new Exception('El nombre es obligatorio.');
    }

    // Manejo de subida de foto (opcional) — JPEG sólo, max 5MB
    $fotoPath = null;
    if (!empty($_FILES['foto']) && isset($_FILES['foto']['error']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['foto']['size'] > $maxSize) {
            throw new Exception('La foto supera el tamaño máximo (5MB).');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['foto']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, ['image/jpeg', 'image/pjpeg'], true)) {
            throw new Exception('Formato de foto no permitido. Use JPG/JPEG.');
        }

        $uploadDir = __DIR__ . '/imagenes/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception('No se pudo crear el directorio de subida.');
            }
        }

        $filename = 'socio_' . time() . '_' . bin2hex(random_bytes(6)) . '.jpg';
        $dest = $uploadDir . '/' . $filename;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
            throw new Exception('Error subiendo la foto.');
        }

        // Ruta relativa para guardar en BD (ajusta si sirves uploads desde otra ruta)
        $fotoPath = 'imagenes/' . $filename;
    }

    if ($id) {
        // UPDATE
        $fields = ['nombre = :nombre', 'telefono = :telefono'];
        $params = [':nombre' => $nombre, ':telefono' => $telefono, ':id' => $id];

        if ($password !== '') {
            $fields[] = 'clave = :clave';
            $params[':clave'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($fotoPath !== null) {
            $fields[] = 'foto = :foto';
            $params[':foto'] = $fotoPath;
        }

        $sql = 'UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $resultId = $id;
    } else {
        // INSERT
        $cols = ['nombre', 'telefono'];
        $placeholders = [':nombre', ':telefono'];
        $params = [':nombre' => $nombre, ':telefono' => $telefono];

        if ($password !== '') {
            $cols[] = 'clave';
            $placeholders[] = ':clave';
            $params[':clave'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($fotoPath !== null) {
            $cols[] = 'foto';
            $placeholders[] = ':foto';
            $params[':foto'] = $fotoPath;
        }

        $sql = 'INSERT INTO usuarios (' . implode(',', $cols) . ') VALUES (' . implode(',', $placeholders) . ')';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $resultId = (int) $pdo->lastInsertId();
    }

    // Determinar si devolver JSON (AJAX) o redirigir (submit normal)
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || str_contains($accept, 'application/json');

    if ($isAjax) {
        respond_json(['success' => true, 'id' => $resultId]);
    } else {
        header('Location: socio.php?id=' . $resultId . '#admin-socios');
        exit;
    }

} catch (Exception $e) {
    $msg = $e->getMessage();
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || str_contains($accept, 'application/json');

    if ($isAjax) {
        respond_json(['success' => false, 'error' => $msg], 400);
    } else {
        header('Location: socio.php?error=' . urlencode($msg));
        exit;
    }
}
?>