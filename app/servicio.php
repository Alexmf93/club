<?php
require_once 'db_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php 
include "menu.php";
menu();
?>
    <div class="container2">
        <main>
            <section id="equipos" class="services-section">
        <div class="container">
            <?php
            try {
                $pdo = new PDO(
                    "mysql:host=$db_host;dbname=$db_name",
                    $db_user,
                    $db_password
                );

                $stmt = $pdo->query("SELECT * FROM servicios LIMIT 4");
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Nuestros servicios:</h2>';
                    echo '<div class="services-list">';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<article class='services-list'>
                        <div class='service-item'>
                            <h3>$row[descripcion]</h3>
                            <p>$row[duracion] €</p>
                            <p>$row[precio] min</p>
                        </div>
                        </article>";
                    }
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo '<p class="error">❌ Error de conexión: ' . $e->getMessage() . '</p>';
            }
            // Formulario de búsqueda
    echo '<h2>Servicios:</h2>';
    echo '<form method="get" action="" class="search-form" style="margin-bottom:1rem;">
            <input type="text" name="q" value="'.htmlspecialchars($search).'" placeholder="Buscar por nombre o teléfono" />
            <button type="submit" class="button button-primary">Buscar</button>
            <a href="socio.php" class="button button-secondary" style="margin-left:0.5rem;">Limpiar</a>
          </form>';

    echo '<div class="news-grid">';
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<article class='news-card socios-card'>
                <img class='img_usuario' src='$row[foto]' alt='$row[nombre]'>
                <div class='news-content'>
                <h3>$row[nombre]</h3>
                <p>Rol: $row[rol]</p>
                <p>Teléfono: $row[telefono]</p>
                <p>Fecha de registro: $row[fecha_registro]</p>
            <a href='socio.php?id=$row[id]#admin-socios' class='button button-primary'>Modificar</a>
        </div>
    </article>";
        }
    } else {
        echo "<p>No se han encontrado socios.</p>";
    }
    echo '</div>';
    ?>
            <section id="admin-servicios">
                <div class="container">
                    <h2>Gestión de Servicios</h2>
                    <form action="" method="post" enctype="multipart/form-data" id="formularioServicio">
                        <div class="formulario">
                            <div class="form-group">
                                <label for="nombre2">Nombre</label>
                                <input type="text" name="nombre2" id="nombre2"/>
                                <span id="nombre2Error" class="error"></span>
                            </div>
                            <div class="form-group">
                                <label for="duracion">Duración(min)</label>
                                <input type="number" name="duracion" id="duracion"/>
                                <span id="duracionError" class="error"></span>
                            </div>
                            <div class="form-group">
                                <label for="precio">Precio</label>
                                <input type="number" name="precio" id="precio"/>
                                <span id="precioError" class="error"></span>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="button button-primary">Enviar</button>
                                <button type="reset" class="button button-secondary">Cancelar</button>
                                <a href="paginaPrincipal.php" class="button button-secondary">Atras</a>
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