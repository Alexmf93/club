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
                            <img src='$row[imagen]' alt='$row[titulo]'>
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
            <h2>Nuestros Equipos</h2>
            <ul class="services-list">
                <li class="service-item"><h3>Fútbol Sala</h3><p>Entrenos 3 veces por semana, torneos y competición anual.</p></li>
                <li class="service-item"><h3>Baloncesto</h3><p>Plantilla senior y junior, alto rendimiento y preparación técnica.</p></li>
                <li class="service-item"><h3>Boxeo</h3><p>Entrenamiento funcional, sparring controlado y sesiones personales.</p></li>
                <li class="service-item"><h3>Pádel</h3><p>Ligas internas, reservas de pista y clases privadas con entrenador.</p></li>
            </ul>
        </div>
    </section>

    <!-- NOTICIAS -->
    <section id="noticias" class="latest-news-section">
        <div class="container">
            <h2>Últimas Noticias</h2>
            <div class="news-grid">
                <article class="news-card">
                    <img src="imagenes/premio.jpg" alt="Victoria Liga">
                    <div class="news-content">
                        <h3>Victoria del Fútbol Sala en Liga</h3>
                        <p>El equipo senior vence 4-2 y se coloca primero en la clasificación.</p>
                        <a href="noticias.php" class="read-more">Leer más</a>
                    </div>
                </article>
                <article class="news-card">
                    <img src="imagenes/padel.jpg" alt="Torneo Pádel">
                    <div class="news-content">
                        <h3>Nuevo Torneo Local de Pádel</h3>
                        <p>Abiertas inscripciones para el torneo mixto del próximo mes.</p>
                        <a href="noticias.php" class="read-more">Leer más</a>
                    </div>
                </article>
                <article class="news-card">
                    <img src="imagenes/boxeo.jpg" alt="Sesión Boxeo">
                    <div class="news-content">
                        <h3>Entrenamiento especial de Boxeo</h3>
                        <p>Sábado a las 18:00, sesión intensiva con entrenador invitado.</p>
                        <a href="noticias.php" class="read-more">Leer más</a>
                    </div>
                </article>
            </div>
        </div>
    </section>
</main>

<?php

include "footer.php";
pie();
?>
<!-- Scripts -->
<script src="js/script.js"></script>

</body>
</html>
