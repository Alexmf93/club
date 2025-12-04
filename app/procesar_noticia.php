<?php
// filepath: c:\Users\AULA4-2DAW\Desktop\club\app\procesar_noticia.php
require_once 'db_config.php';

function respond_json($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: noticia.php');
    exit;
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $contenido = isset($_POST['noticia']) ? trim($_POST['noticia']) : '';
    $fecha_raw = isset($_POST['fecha_publicacion']) ? trim($_POST['fecha_publicacion']) : '';
    $fecha_publicacion = $fecha_raw !== '' ? $fecha_raw . ' 00:00:00' : null;

    if ($titulo === '') throw new Exception('El título es obligatorio.');
    if (strlen($titulo) < 5 || strlen($titulo) > 200) throw new Exception('El título debe tener entre 5 y 200 caracteres.');
    if ($contenido === '') throw new Exception('El contenido es obligatorio.');
    if (strlen($contenido) < 20) throw new Exception('El contenido debe tener al menos 20 caracteres.');
    if ($fecha_publicacion === null) throw new Exception('La fecha de publicación es obligatoria.');

    // Validar fecha posterior a hoy
    $fecha_obj = new DateTime($fecha_publicacion);
    $hoy = new DateTime('today');
    if ($fecha_obj < $hoy) {
        throw new Exception('La fecha de publicación debe ser posterior a hoy.');
    }

    // Manejo de foto (obligatoria, JPEG, máx 5MB)
    $imagen_ruta = null;
    if (empty($_FILES['fotoNoticia']) || $_FILES['fotoNoticia']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('La imagen es obligatoria.');
    }

    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($_FILES['fotoNoticia']['size'] > $maxSize) {
        throw new Exception('La imagen no debe superar los 5MB de tamaño.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['fotoNoticia']['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ['image/jpeg', 'image/pjpeg'], true)) {
        throw new Exception('El archivo debe estar en formato JPEG (.jpg o .jpeg).');
    }

    $uploadDir = __DIR__ . '/uploads/noticias';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception('No se pudo crear el directorio de subida.');
        }
    }

    $filename = 'noticia_' . time() . '_' . bin2hex(random_bytes(6)) . '.jpg';
    $dest = $uploadDir . '/' . $filename;
    if (!move_uploaded_file($_FILES['fotoNoticia']['tmp_name'], $dest)) {
        throw new Exception('Error subiendo la imagen.');
    }

    $imagen_ruta = 'uploads/noticias/' . $filename;

    // INSERT nueva noticia
    $sql = "INSERT INTO noticia (titulo, contenido, imagen, fecha_publicacion) 
            VALUES (:titulo, :contenido, :imagen, :fecha_publicacion)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $titulo,
        ':contenido' => $contenido,
        ':imagen' => $imagen_ruta,
        ':fecha_publicacion' => $fecha_publicacion,
    ]);
    $resultId = (int)$pdo->lastInsertId();

    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
              || strpos($accept, 'application/json') !== false;

    if ($isAjax) {
        respond_json(['success' => true, 'id' => $resultId]);
    } else {
        header('Location: noticia.php');
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
        header('Location: noticia.php?error=' . urlencode($msg));
        exit;
    }
}
?>