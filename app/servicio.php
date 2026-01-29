<?php
session_start();
require_once 'db_config.php';
?>
<?php include "head.php"; ?>
<body>
<?php
include "menu.php";
menu();

// Mostrar banner (flash) si hay mensaje en la query string
if (!empty($_GET['msg'])) {
    echo '<div class="flash-success">' . htmlspecialchars($_GET['msg']) . '</div>';
} elseif (!empty($_GET['error'])) {
    echo '<div class="flash-error">' . htmlspecialchars($_GET['error']) . '</div>';
}

// Conexión global
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<p class="error">❌ Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

// Búsqueda por descripcion (nombre del servicio)
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
// Cargar servicio si viene ?id= para editar
$srvId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
$serviceData = null;
if ($srvId) {
    $st = $pdo->prepare("SELECT * FROM servicios WHERE id = :id");
    $st->execute([':id' => $srvId]);
    $serviceData = $st->fetch(PDO::FETCH_ASSOC) ?: null;
}
?>
<div class="container2">
    <main>
        <section id="equipos" class="services-section">
            <div class="container">
                <h2>Servicios</h2>

                <form method="get" action="servicio.php" class="search-form" style="margin-bottom:1rem;">
                    <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar por nombre del servicio" />
                    <button type="submit" class="button button-primary">Buscar</button>
                    <a href="servicio.php" class="button button-secondary" style="margin-left:0.5rem;">Limpiar</a>
                </form>

<?php
// Consulta: si hay búsqueda, filtrar por descripcion; si no, listar todos
if ($search !== '') {
    $sql = "SELECT * FROM servicios WHERE descripcion LIKE :q ORDER BY descripcion ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':q' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM servicios ORDER BY descripcion ASC");
}

if ($stmt && $stmt->rowCount() > 0) {
    echo '<div class="services-list">';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '
                <div class="service-item">
                    <h3>' . htmlspecialchars($row['descripcion']) . '</h3>
                    <p>Duración: ' . htmlspecialchars($row['duracion']) . ' min</p>
                    <p>Precio: ' . htmlspecialchars($row['precio']) . ' €</p>
                    <a href="servicio.php?id=' . urlencode($row['id']) . '#admin-servicios" class="button button-primary">Modificar</a>
                </div>
              ';
    }
    echo '</div>';
} else {
    echo '<p>No se han encontrado servicios.</p>';
}
?>
            </div>
        </section>

        <section id="admin-servicios">
            <div class="container">
                <h2><?php echo $serviceData ? 'Editar Servicio' : 'Insertar Nuevo Servicio'; ?></h2>
                <form action="procesar_servicio.php" method="post" id="formularioServicio">
                    <?php if ($serviceData): ?>
                        <input type="hidden" name="id" value="<?php echo (int)$serviceData['id']; ?>">
                    <?php endif; ?>
                    <div class="formulario">
                        <div class="form-group">
                            <label for="nombre2">Nombre</label>
                            <input type="text" name="nombre2" id="nombre2" value="<?php echo $serviceData ? htmlspecialchars($serviceData['descripcion']) : ''; ?>" />
                            <span id="nombre2Error" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="duracion">Duración (min)</label>
                            <input type="number" name="duracion" id="duracion" value="<?php echo $serviceData ? (int)$serviceData['duracion'] : ''; ?>" />
                            <span id="duracionError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio (€)</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="<?php echo $serviceData ? htmlspecialchars($serviceData['precio']) : ''; ?>" />
                            <span id="precioError" class="error"></span>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="button button-primary"><?php echo $serviceData ? 'Actualizar' : 'Enviar'; ?></button>
                            <button type="reset" class="button button-secondary">Cancelar</button>
                            <a href="servicio.php" class="button button-secondary">Volver</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

    </main>
</div>

<?php
include "footer.php";
pie();
?>
<script src="js/jsServicio.js"></script>
</body>
</html>