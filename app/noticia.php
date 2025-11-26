<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticia</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php 
include "menu.php";
menu();
?>
    <div class="container2">
        <main>
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
                                <span id="fechaError" class="error"></span>
                            </div>
                            <div class="form-group">
                                <label for="fotoNoticia">Foto de la noticia</label>
                                <input type="file" name="fotoNoticia" id="fotoNoticia"/>
                                <span id="fotoError" class="error"></span>
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
    <script src="js/jsNoticia.js"></script>
</body>
</html>