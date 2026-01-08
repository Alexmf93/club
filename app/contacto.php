<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
        include "menu.php";
        menu();
    ?>

        <main>
            <section id="admin-testimonios">
                <div class="container">
                    <h2>Contacto</h2>
                    <form action="" method="post" enctype="multipart/form-data" id="formularioContacto">
                        <div class="formulario">
                            <div class="form-group">
                                <label for="nombre">Introduzca su nombre</label>
                                <input type="text" name="nombre" id="nombre">
                            </div>
                            <div class="form-group">
                                <label for="mail">Introduzca su e mail</label>
                                <input type="email" name="mail" id="mail">
                            </div>
                            <div class="form-group">
                                <label for="problema">Introduzca su consulta</label>
                                <textarea name="problema" id="problema"></textarea>
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
</body>
</html>