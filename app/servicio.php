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