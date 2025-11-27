<?php
require_once 'db_config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Deportivo El Real</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body>

<?php 
include "menu.php";
menu();
?>

<!-- SECCIONES PRINCIPALES -->
<main>

    <!-- HERO -->
    <section id="inicio" class="hero">
        <div class="container">
            <h1>Pasión, disciplina y deporte</h1>
            <p>En el Club Deportivo El Real entrenamos cuerpo y mente. Únete a nuestros equipos, participa en eventos y crece con nosotros.</p>
            <a href="#equipos" class="btn btn-primary">Descubre Nuestros Equipos</a>
                    <div id="noticias">
        <!-- Noticias -->
            <?php
            try {
                $pdo = new PDO(
                    "mysql:host=$db_host;dbname=$db_name",
                    $db_user,
                    $db_password
                );

                // Ejemplo: Mostrar datos de una tabla
                //Para el ejercicio habria que poner limit 3 y un where para que la fecha de publicacion sea posterior a la consulta
                $stmt = $pdo->query("SELECT * FROM noticia WHERE fecha_publicacion < NOW() ORDER BY fecha_publicacion DESC LIMIT 3");
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Noticias destacadas:</h2>';                    
                    echo '<div class="news-grid">';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<article class='news-card'>
                            <img class='new_image' src='$row[imagen]' alt='$row[titulo]'>
                            <div class='news-content'>
                            <h3>$row[titulo]</h3>
                            <p>$row[contenido]</p>
                            <button id='$row[fecha_publicacion]' class='read-more'>Leer más</button>
                    </div>
                </article>";
                    }
                    echo '</div>';

                }
            } catch (PDOException $e) {
                echo '<p class="error">❌ Error de conexión: ' . $e->getMessage() . '</p>';
            }
            ?>
        </div>
        </div>
    </section>
    <!-- EQUIPOS -->
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
            ?>
    <!-- TESTIMONIO -->
    <section id="equipos" class="services-section">
        <div class="container">
            <?php
            try {
                $pdo = new PDO(
                    "mysql:host=$db_host;dbname=$db_name",
                    $db_user,
                    $db_password
                );

                $stmt = $pdo->query("SELECT t.*, u.nombre FROM testimonio t JOIN usuarios u ON t.id_autor = u.id ORDER BY RAND() LIMIT 1");
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Testimonios:</h2>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        //Formatear ka fecha en español ain hora
                        $fechaObj = new DateTime($row['fecha']);
                        $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
                        $mes = $meses[(int)$fechaObj->format('m') - 1];
                        $fechaFormato = $fechaObj->format('j') . ' de ' . $mes . ' de ' . $fechaObj->format('Y');
                        
                        echo "<article class='services-list'>
                        <div class='service-item'>
                            <h3>" . htmlspecialchars($row['nombre']) . "</h3>
                            <p>" . htmlspecialchars($row['contenido']) . "</p>
                            <p>" . htmlspecialchars($fechaFormato) . "</p>
                        </div>
                    </article>";
                    }
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo '<p class="error">❌ Error de conexión: ' . $e->getMessage() . '</p>';
            }
            ?>
            
<?php

include "footer.php";
pie();
?>
<!-- Scripts -->
<script src="js/script.js"></script>

</body>
</html>
