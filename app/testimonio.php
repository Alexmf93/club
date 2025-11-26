<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container2">
        <?php 
include "menu.php";
menu();
?>
<main>
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
                                <a href="paginaPrincipal.php" class="button button-secondary">Atras</a>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            </main>
            <?php
        include "footer.php";
        pie();
        ?>
    </div>
    <script src="js/jsTestimonio.js"></script>
</body>
</html>