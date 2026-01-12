<?php
require_once 'db_config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonios</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php
include "menu.php";
menu();

// Conexión global
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<p class="error">❌ Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}
?>
<div class="container2">
    <main>
        <section id="listado-testimonios" class="services-section">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="container">
                    <div class="message success" role="alert">
                        ✅ <?= htmlspecialchars($_SESSION['success_message']); ?>
                    </div>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php elseif (isset($_SESSION['error_message'])): ?>
                <div class="container">
                    <div class="message error" role="alert">
                        ❌ <?= htmlspecialchars($_SESSION['error_message']); ?>
                    </div>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <div class="container">
                <h2>Testimonios</h2>

<?php
// Obtener todos los testimonios ordenados por fecha (más reciente primero)
try {
    $sql = "SELECT t.*, u.nombre AS autor 
            FROM testimonio t
            JOIN usuarios u ON t.id_autor = u.id
            ORDER BY t.fecha DESC";
    $stmt = $pdo->query($sql);

    if ($stmt && $stmt->rowCount() > 0) {
        echo '<div class="services-list">';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formatear fecha en español
            $fechaObj = new DateTime($row['fecha']);
            $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            $mes = $meses[(int)$fechaObj->format('m') - 1];
            $fechaFormato = $fechaObj->format('j') . ' de ' . $mes . ' de ' . $fechaObj->format('Y');

            echo '<article class="service-card">
                    <div class="service-item">
                        <h3>' . htmlspecialchars($row['autor']) . '</h3>
                        <p>' . htmlspecialchars($row['contenido']) . '</p>
                        <p><small>' . htmlspecialchars($fechaFormato) . '</small></p>
                    </div>
                  </article>';
        }
        echo '</div>';
    } else {
        echo '<p>No hay testimonios registrados aún.</p>';
    }
} catch (PDOException $e) {
    echo '<p class="error">❌ Error al obtener testimonios: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
            </div>
        </section>
        <?php if(isset($_SESSION['username'])): ?>
        <section id="admin-testimonios">
            <div class="container">
                <h2>Insertar Nuevo Testimonio</h2>
                <form action="procesar_testimonio.php" method="post" id="testimonioForm">
                    <div class="formulario">
                        <div class="form-group">
                            <label for="id_autor">Autor (Socio)</label>
                            <input type="text"name="nombre" id="nombre" value="<?php echo $_SESSION['username'] ?>">
                            <input type="hidden"name="id_autor" id="id_autor" value="<?php echo $_SESSION['user_id'] ?>">

                        </div>
                        <div class="form-group">
                            <label for="contenido">Testimonio</label>
                            <textarea name="contenido" id="contenido" placeholder="Escribe tu testimonio aquí..."></textarea>
                            <span id="contenidoError" class="error"></span>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="button button-primary">Enviar</button>
                            <button type="reset" class="button button-secondary">Cancelar</button>
                            <a href="index.php" class="button button-secondary">Atrás</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <?php endif; ?>
        <!-- final -->
    </main>
</div>

<?php
include "footer.php";
pie();
?>
<script src="js/jsTestimonio.js"></script>
</body>
</html>