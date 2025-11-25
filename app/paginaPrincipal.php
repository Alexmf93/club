<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Deportivo El Real</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/estilos.css"> <!-- para formularios -->
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
                    <img src="images/noticia1.jpg" alt="Victoria Liga">
                    <div class="news-content">
                        <h3>Victoria del Fútbol Sala en Liga</h3>
                        <p>El equipo senior vence 4-2 y se coloca primero en la clasificación.</p>
                        <a href="noticias.php" class="read-more">Leer más</a>
                    </div>
                </article>
                <article class="news-card">
                    <img src="images/noticia2.jpg" alt="Torneo Pádel">
                    <div class="news-content">
                        <h3>Nuevo Torneo Local de Pádel</h3>
                        <p>Abiertas inscripciones para el torneo mixto del próximo mes.</p>
                        <a href="noticias.php" class="read-more">Leer más</a>
                    </div>
                </article>
                <article class="news-card">
                    <img src="images/noticia3.jpg" alt="Sesión Boxeo">
                    <div class="news-content">
                        <h3>Entrenamiento especial de Boxeo</h3>
                        <p>Sábado a las 18:00, sesión intensiva con entrenador invitado.</p>
                        <a href="noticias.php" class="read-more">Leer más</a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- FORMULARIOS ADMIN -->
    <section id="admin-testimonios">
        <div class="container">
            <h2>Gestión de Testimonios</h2>
            <form action="" method="post" enctype="multipart/form-data" id="testimonioForm">
                <div class="formulario">
                    <div class="form-group">
                        <label for="autor">Autor</label>
                        <input type="text" name="autor" id="autor"/>
                        <span id="autorError" class="error"></span>
                    </div>
                    <div class="form-group">
                        <label for="testimonio">Testimonio</label>
                        <textarea name="testimonio" id="testimonio"></textarea>
                        <span id="testimonioError" class="error"></span>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="button button-primary">Enviar</button>
                        <button type="reset" class="button button-secondary">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section id="admin-socios">
        <div class="container">
            <h2>Gestión de Socios</h2>
            <form action="" method="post" enctype="multipart/form-data" id="formularioSocio">
                <div class="formulario">
                    <div class="form-group-columns">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" id="nombre"/>
                            <span id="nombreError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="usuario">Usuario</label>
                            <input type="text" name="usuario" id="usuario"/>
                            <span id="usuarioError" class="error"></span>
                        </div>
                    </div>
                    <div class="form-group-columns">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <input type="number" name="edad" id="edad"/>
                            <span id="edadError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password"/>
                            <span id="contraseñaError" class="error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="tel" name="telefono" id="telefono"/>
                        <span id="telefonoError" class="error"></span>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto del socio</label>
                        <input type="file" name="foto" id="foto"/>
                        <span id="fotoError" class="error"></span>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="button button-primary">Enviar</button>
                        <button type="reset" class="button button-secondary">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

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
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section id="admin-noticias">
        <div class="container">
            <h2>Gestión de Noticias</h2>
            <form action="" method="post" enctype="multipart/form-data" id="noticiaForm">
                <div class="formulario">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo"/>
                        <span id="tituloError" class="error"></span>
                    </div>
                    <div class="form-group">
                        <label for="contenido">Noticia</label>
                        <textarea name="noticia" id="contenido"></textarea>
                        <span id="noticiaError" class="error"></span>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha de publicación</label>
                        <input type="date" name="fecha" id="fecha"/>
                    </div>
                    <div class="form-group">
                        <label for="fotoNoticia">Foto de la noticia</label>
                        <input type="file" name="fotoNoticia" id="fotoNoticia"/>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="button button-primary">Enviar</button>
                        <button type="reset" class="button button-secondary">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section id="admin-citas">
        <div class="container">
            <h2>Gestión de Citas</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="formulario">
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <input type="text" name="cliente" id="cliente" required/>
                    </div>
                    <div class="form-group">
                        <label for="mensajeCita">Mensaje</label>
                        <textarea name="mensajeCita" id="mensajeCita"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fechaCita">Fecha</label>
                        <input type="date" name="fechaCita" id="fechaCita"/>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="button button-primary">Enviar</button>
                        <button type="reset" class="button button-secondary">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

</main>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="script.js"></script>
<script src="js/jsTestimonio.js"></script>
<script src="js/jsServicio.js"></script>
<script src="js/jsNoticia.js"></script>

</body>
</html>
