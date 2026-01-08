<?php
session_start();
require_once 'db_config.php';
if(!(isset($_SESSION['rol']) && $_SESSION['rol'] == 'administrador')){
    header("Location: paginaPrincipal.php");
    exit;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Socio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
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


    // Conexión a BD global
    try {
        $pdo = new PDO(
            "mysql:host=$db_host;dbname=$db_name",
            $db_user,
            $db_password
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo '<p class="error">❌ Error de conexión: ' . $e->getMessage() . '</p>';
        exit;
    }

    // Buscador por nombre o teléfono (GET ?q=)
    $search = isset($_GET['q']) ? trim($_GET['q']) : '';

    if ($search !== '') {
        $sql = "SELECT * FROM usuarios WHERE nombre LIKE :q OR telefono LIKE :q ORDER BY nombre ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':q' => "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM usuarios ORDER BY nombre ASC");
    }

    // Formulario de búsqueda
    echo '<h2>Socios:</h2>';
    echo '<form method="get" action="" class="search-form" style="margin-bottom:1rem;">
            <input type="text" name="q" value="'.htmlspecialchars($search).'" placeholder="Buscar por nombre o teléfono" />
            <button type="submit" class="button button-primary">Buscar</button>
            <a href="socio.php" class="button button-secondary" style="margin-left:0.5rem;">Limpiar</a>
          </form>';

    echo '<div class="news-grid">';
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <article class='news-card socios-card'>
                <img class='img_usuario' src='<?= htmlspecialchars($row['foto']) ?>' alt='<?= htmlspecialchars($row['nombre']) ?>'>
                <div class='news-content'>
                    <h3><?= htmlspecialchars($row['nombre']) ?></h3>
                    <p>Rol: <?= htmlspecialchars($row['rol']) ?></p>
                    <p>Teléfono: <?= htmlspecialchars($row['telefono']) ?></p>
                    <p>Fecha de registro: <?= htmlspecialchars($row['fecha_registro']) ?></p>
                    <a href='socio.php?id=<?= (int)$row['id'] ?>#admin-socios' class='button button-primary'>Modificar</a>
                </div>
            </article>
            <?php
        }
    } else {
        echo "<p>No se han encontrado socios.</p>";
    }
    echo '</div>';
    ?>

    <div class="container2">
        <main>
            <section id="admin-socios">
                <div class="container">
                    <h2>Insertar Nuevos Socios</h2>
                    <?php
                    // Cargar datos del socio si viene ID
                    $socioId = isset($_GET['id']) ? (int)$_GET['id'] : null;
                    $socioData = null;

                    if ($socioId) {
                        $stmtSocio = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
                        $stmtSocio->execute([':id' => $socioId]);
                        $socioData = $stmtSocio->fetch(PDO::FETCH_ASSOC);
                    }
                    ?>
                    <form action="" method="post" enctype="multipart/form-data" id="formularioSocio">
                        <div class="formulario">
                            <?php if ($socioData): ?>
                                <input type="hidden" name="id" value="<?php echo $socioData['id']; ?>">
                            <?php endif; ?>
                            <div class="form-group-columns">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" name="nombre" id="nombre" placeholder="Ingresa tu nombre" value="<?php echo $socioData ? htmlspecialchars($socioData['nombre']) : ''; ?>"/>
                                    <span id="nombreError" class="error"></span>
                                </div>
                              
                            
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" name="password" id="password" placeholder="<?php echo $socioData ? 'Dejar en blanco para no cambiar' : ''; ?>"/>
                                    <span id="contraseñaError" class="error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" name="telefono" id="telefono" value="<?php echo $socioData ? $socioData['telefono'] : ''; ?>"/>
                                <span id="telefonoError" class="error"></span>
                            </div>
                            <div class="form-group">
                                <label for="foto">Foto del socio</label>
                                <input type="file" name="foto" id="foto"/>
                                <span id="fotoError" class="error"></span>
                                <?php if ($socioData && $socioData['foto']): ?>
                                    <p><small>Foto actual: <img src="<?php echo $socioData['foto']; ?>" style="max-width:100px;"></small></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="button button-primary"><?php echo $socioData ? 'Actualizar' : 'Enviar'; ?></button>
                                <!-- <button type="reset" class="button button-secondary">Cancelar</button>8 -->
                                <a href='socio.php' class='button button-primary'>Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            
        </main>
    </div>
    <?php
        // Mostrar banner (flash) si hay mensaje en la query string
    if (!empty($_GET['msg'])) {
        echo '<div class="flash-success">' . htmlspecialchars($_GET['msg']) . '</div>';
        
    } elseif (!empty($_GET['error'])) {
        echo '<div class="flash-error">' . htmlspecialchars($_GET['error']) . '</div>';
    }
        include "footer.php";
        pie();
    ?>
    <script src="js/jsSocio.js?v=1.1"></script>
</body>
</html>