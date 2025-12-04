<?php
require_once 'db_config.php';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id === null) throw new Exception('ID de cita inválido.');

    // Obtener cita
    $stmt = $pdo->prepare("SELECT fecha_cita FROM citas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cita) throw new Exception('Cita no encontrada.');

    // Validar que no sea del día actual
    $hoy = date('Y-m-d');
    $fecha_cita = substr($cita['fecha_cita'], 0, 10);
    if ($fecha_cita === $hoy) {
        throw new Exception('No se pueden borrar citas del día actual.');
    }

    // Borrar
    $stmt_delete = $pdo->prepare("DELETE FROM citas WHERE id = :id");
    $stmt_delete->execute([':id' => $id]);

    header('Location: cita.php');
    exit;

} catch (Exception $e) {
    header('Location: cita.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>