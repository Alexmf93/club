<?php
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cita.php');
    exit;
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_socio = isset($_POST['id_socio']) && $_POST['id_socio'] !== '' ? (int)$_POST['id_socio'] : null;
    $id_servicio = isset($_POST['id_servicio']) && $_POST['id_servicio'] !== '' ? (int)$_POST['id_servicio'] : null;
    $fecha_cita = isset($_POST['fecha_cita']) ? trim($_POST['fecha_cita']) : '';
    $hora_cita = isset($_POST['hora_cita']) ? trim($_POST['hora_cita']) : '';

    if ($id_socio === null) throw new Exception('Debe seleccionar un socio.');
    if ($id_servicio === null) throw new Exception('Debe seleccionar un servicio.');
    if ($fecha_cita === '') throw new Exception('Debe seleccionar una fecha.');
    if ($hora_cita === '') throw new Exception('Debe seleccionar una hora.');

    // Validar que la fecha sea posterior a hoy
    $hoy = date('Y-m-d');
    if ($fecha_cita <= $hoy) {
        throw new Exception('La fecha de la cita debe ser posterior a hoy.');
    }

    // Validar que no exista otra cita para el mismo socio en la misma fecha y hora
    $sql_check = "SELECT COUNT(*) as count FROM citas 
                  WHERE id_socio = :id_socio 
                  AND DATE(fecha_cita) = :fecha 
                  AND TIME(fecha_cita) = :hora";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([
        ':id_socio' => $id_socio,
        ':fecha' => $fecha_cita,
        ':hora' => $hora_cita
    ]);
    $check = $stmt_check->fetch(PDO::FETCH_ASSOC);
    if ($check['count'] > 0) {
        throw new Exception('Este socio ya tiene una cita en esa fecha y hora.');
    }

    // INSERT
    $fecha_hora = $fecha_cita . ' ' . $hora_cita . ':00';
    $sql = "INSERT INTO citas (id_socio, id_servicio, fecha_cita, hora_cita) 
            VALUES (:id_socio, :id_servicio, :fecha, :hora)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_socio' => $id_socio,
        ':id_servicio' => $id_servicio,
        ':fecha' => $fecha_cita,
        ':hora' => $hora_cita
    ]);

    header('Location: cita.php');
    exit;

} catch (Exception $e) {
    header('Location: cita.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>