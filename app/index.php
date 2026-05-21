<?php
session_start();
require_once 'db_config.php';
?>

<?php include "head.php"; ?>

<?php 
    include "menu.php";
   
    menu();

    // Conexión ya establecida en db_config.php, usamos $pdo directamente.
?>

<!-- SECCIONES PRINCIPALES -->
<main>

    <!-- HERO & NOTICIAS -->
    <section id="inicio" class="hero">
        <div class="container">
            <h1>Pasión, disciplina y deporte</h1>
            <p>En el Club Deportivo El Real entrenamos cuerpo y mente. Únete a nuestros equipos, participa en eventos y crece con nosotros.</p>
            <a href="#equipos" class="btn btn-primary">Descubre Nuestros Equipos</a>
        </div>
    </section>

    <!-- Video -->

    <section class="video-section">
        <div class="container">
            <video controls preload="metadata" width="100%" style="max-width: 800px;" poster="assets/imagen-preview.jpg">
                <source src="assets/videoPromocional.mp4" type="video/mp4">
                <source src="assets/videoPromocional.webm" type="video/webm">
                Tu navegador no soporta la etiqueta video.
            </video>
        </div>
    </section>

    <!-- NOTICIAS -->
    <section id="noticias" class="news-section-principal">
        <div class="container">
            <?php
                // Consulta para las 3 noticias más recientes ya publicadas
                $stmt = $pdo->query("SELECT * FROM noticia WHERE fecha_publicacion <= NOW() ORDER BY fecha_publicacion DESC LIMIT 3");
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Noticias destacadas</h2>';                    
                    echo '<div class="news-grid">';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $contenido_corto = strlen($row['contenido']) > 150 ? substr($row['contenido'], 0, 150) . '...' : $row['contenido'];
                        echo "
                        <article class='news-card'>
                            <img class='new_image' src='" . htmlspecialchars($row['imagen']) . "' alt='" . htmlspecialchars($row['titulo']) . "'>
                            <div class='news-content'>
                                <h3>" . htmlspecialchars($row['titulo']) . "</h3>
                                <p>" . htmlspecialchars($contenido_corto) . "</p>
                                <button class='button button-primary read-more' 
                                        data-titulo='" . htmlspecialchars($row['titulo'], ENT_QUOTES) . "' 
                                        data-contenido='" . htmlspecialchars($row['contenido'], ENT_QUOTES) . "' 
                                        data-imagen='" . htmlspecialchars($row['imagen'], ENT_QUOTES) . "'
                                        data-fecha='" . htmlspecialchars($row['fecha_publicacion']) . "'>
                                    Leer más
                                </button>
                            </div>
                        </article>";
                    }
                    echo '</div>';
                    echo '<div style="text-align: center; margin-top: 2rem;"><a href="noticia.php" class="button button-secondary">Ver todas las noticias</a></div>';
                }
            ?>
        </div>
    </section>

    <!-- EQUIPOS -->
    <section id="equipos" class="services-section">
        <div class="container">
            <?php
                // Consulta para 4 servicios
                $stmt = $pdo->query("SELECT * FROM servicios LIMIT 4");
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Nuestros servicios</h2>';
                    echo '<div class="services-list">';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "
                        <article class='service-item'>
                            <h3>" . htmlspecialchars($row['descripcion']) . "</h3>
                            <p>Duración: " . htmlspecialchars($row['duracion']) . " min</p>
                            <p>Precio: " . htmlspecialchars($row['precio']) . " €</p>
                        </article>";
                    }
                    echo '</div>';
                }
            ?>
        </div>
    </section>

    <!-- TESTIMONIO -->
    <section id="testimonios" class="services-section">
        <div class="container">
            <?php
                // Consulta para un testimonio aleatorio
                $stmt = $pdo->query("SELECT t.*, u.nombre FROM testimonio t JOIN usuarios u ON t.id_autor = u.id ORDER BY RAND() LIMIT 1");
                if ($stmt->rowCount() > 0) {
                    echo '<h2>Testimonios</h2>';
                    echo '<div class="services-list">';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Formatear la fecha en español sin hora
                        $fechaObj = new DateTime($row['fecha']);
                        $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
                        $mes = $meses[(int)$fechaObj->format('m') - 1];
                        $fechaFormato = $fechaObj->format('j') . ' de ' . $mes . ' de ' . $fechaObj->format('Y');

                        echo "
                        <article class='service-item'>
                            <h3>" . htmlspecialchars($row['nombre']) . "</h3>
                            <blockquote><p>\"" . htmlspecialchars($row['contenido']) . "\"</p></blockquote>
                            <cite>" . htmlspecialchars($fechaFormato) . "</cite>
                        </article>";
                    }
                    echo '</div>';
                }
            ?>
        </div>
    </section>

    <!-- TIEMPO -->
    <section id="tiempo" class="weather-section">
        <div class="container">
            <h2>El tiempo</h2>
            <div class="weather-search">
                <input type="text" id="ciudadInput" placeholder="Escribe una ciudad..." autocomplete="off">
                <button id="btnBuscarCiudad" class="button button-primary">Buscar</button>
            </div>
            <div id="weatherCargando" class="weather-loading" style="display:none;">Cargando datos...</div>
            <div id="weatherResultados" class="weather-results"></div>
        </div>
    </section>
</main>
<!-- Chatbot -->
    <button class="chatbot-toggle" id="chatbotToggle">💬</button>
    
    <div class="chatbot-container" id="chatbotContainer">
        <div class="chatbot-header">
            <h3>🎾 Club de Socios</h3>
            <p>Asistente Virtual</p>
        </div>
        
        <div class="chatbot-messages" id="chatbotMessages"></div>
        
        <div class="quick-replies" id="chatbotQuickReplies"></div>
        
        <div class="chatbot-input">
            <input type="text" id="chatbotUserInput" placeholder="Escribe tu mensaje...">
            <button onclick="chatbotSendMessage()">Enviar</button>
        </div>
    </div>

<?php
    include "footer.php";
    pie();
?>
<!-- Scripts -->
<script src="js/script.js"></script>
<script src="js/chatbot.js"></script>
<script src="js/weather.js" defer></script>
<!-- <script src="js/jsNoticia.js"></script> -->
<!-- <script src="js/modalNoticia.js"></script> -->
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js');
  }
</script>


</body>
</html>
