<?php
require_once 'db_config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Aplicación Web</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container">
        <h1>Aplicación Web con Docker</h1>

        <div id="noticias">
            <?php
            try {
                $pdo = new PDO(
                    "mysql:host=$db_host;dbname=$db_name",
                    $db_user,
                    $db_password
                );
                echo '<p class="success">✅ Conexión a base de datos exitosa</p>';

                // Ejemplo: Mostrar datos de una tabla
                //Para el ejercicio habria que poner limit 3 y un where para que la fecha de publicacion sea posterior a la consulta
                $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 5");
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Usuarios registrados:</h2>';
                    echo '<ul id="user-list">';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li>' . htmlspecialchars($row['nombre']) . '</li>';
                    }
                    echo '</ul>';
                }
            } catch (PDOException $e) {
                echo '<p class="error">❌ Error de conexión: ' . $e->getMessage() . '</p>';
            }
            ?>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>

</html>