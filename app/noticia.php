<?php
session_start();
require_once 'db_config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
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

// Conexión global
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<p class="error">❌ Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

// Paginación
$noticias_por_pagina = 4;
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

// Calcular offset
$offset = ($pagina_actual - 1) * $noticias_por_pagina;

// Obtener total de noticias
$stmtTotal = $pdo->query("SELECT COUNT(*) as total FROM noticia");
$totalNoticias = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
$total_paginas = ceil($totalNoticias / $noticias_por_pagina);

// Asegurar que pagina_actual no exceda total_paginas
if ($pagina_actual > $total_paginas && $total_paginas > 0) $pagina_actual = $total_paginas;
?>
<div class="container2">
    <main>
        <section id="listado-noticias" class="services-section">
            <div class="container">
                <h2>Noticias</h2>

<?php
// Obtener noticias para la página actual (ordenadas por fecha descendente)
try {
    $sql = "SELECT * FROM noticia  
            ORDER BY fecha_publicacion DESC 
            LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $noticias_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt && $stmt->rowCount() > 0) {
        echo '<div class="news-grid">';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formatear fecha en español
            $fechaObj = new DateTime($row['fecha_publicacion']);
            $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            $mes = $meses[(int)$fechaObj->format('m') - 1];
            $fechaFormato = $fechaObj->format('j') . ' de ' . $mes . ' de ' . $fechaObj->format('Y');

            // Truncar contenido a 150 caracteres
            $contenido_corto = strlen($row['contenido']) > 150 
                ? substr($row['contenido'], 0, 150) . '...' 
                : $row['contenido'];

            echo '<article class="news-card">
                    <img src="' . htmlspecialchars($row['imagen']) . '" alt="' . htmlspecialchars($row['titulo']) . '" loading="lazy" width="400" height="300" class="new_image">
                    <div class="news-content">
                        <h3>' . htmlspecialchars($row['titulo']) . '</h3>
                        <p>' . htmlspecialchars($contenido_corto) . '</p>
                        <span class="news-date">' . htmlspecialchars($fechaFormato) . '</span>
                        <button class="button button-primary read-more" data-id="' . (int)$row['id'] . '" 
                                data-titulo="' . htmlspecialchars($row['titulo'], ENT_QUOTES) . '" 
                                data-contenido="' . htmlspecialchars($row['contenido'], ENT_QUOTES) . '" 
                                data-imagen="' . htmlspecialchars($row['imagen'], ENT_QUOTES) . '"
                                data-fecha="' . htmlspecialchars($row['fecha_publicacion']) . '">
                            Ver más
                        </button>
                    </div>
                  </article>';
        }
        echo '</div>';

        // Paginación
        if ($total_paginas > 1) {
            echo '<div class="pagination" style="text-align: center; margin-top: 2rem;">';
            
            // Botón anterior
            if ($pagina_actual > 1) {
                echo '<a href="noticia.php?pagina=' . ($pagina_actual - 1) . '" class="button button-secondary">← Anterior</a> ';
            }

            // Números de página
            for ($i = 1; $i <= $total_paginas; $i++) {
                if ($i === $pagina_actual) {
                    echo '<span class="button" style="background-color: #ff6b6b; cursor: default;">' . $i . '</span> ';
                } else {
                    echo '<a href="noticia.php?pagina=' . $i . '" class="button button-secondary">' . $i . '</a> ';
                }
            }

            // Botón siguiente
            if ($pagina_actual < $total_paginas) {
                echo '<a href="noticia.php?pagina=' . ($pagina_actual + 1) . '" class="button button-secondary">Siguiente →</a>';
            }

            echo '</div>';
        }
    } else {
        echo '<p>No hay noticias disponibles.</p>';
    }
} catch (PDOException $e) {
    echo '<p class="error">❌ Error al obtener noticias: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>
            </div>
        </section>

        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'administrador'): ?>
        <section id="admin-noticias">
            <div class="container">
                <h2>Insertar Nueva Noticia</h2>
                <form action="procesar_noticia.php" method="post" enctype="multipart/form-data" id="noticiaForm">
                    <div class="formulario">
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" name="titulo" id="titulo" placeholder="Título de la noticia"/>
                            <span id="tituloError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="contenido">Contenido</label>
                            <textarea name="noticia" id="contenido" placeholder="Escribe el contenido de la noticia..."></textarea>
                            <span id="noticiaError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="fecha_publicacion">Fecha de publicación</label>
                            <input type="date" name="fecha_publicacion" id="fecha_publicacion"/>
                            <span id="fechaError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="fotoNoticia">Foto de la noticia (JPEG, máx 5MB)</label>
                            <input type="file" name="fotoNoticia" id="fotoNoticia"/>
                            <span id="fotoError" class="error"></span>
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
    </main>
</div>

<?php
include "footer.php";
pie();
?>
<script src="js/jsNoticia.js"></script>
<!-- <script src="js/modalNoticia.js"></script> -->
 <script src="js/script.js"></script>
</body>
</html>