<?php include "head.php"; ?>
<body>
        <?php
include "menu.php";

menu();
?>

<h2>Gestión de citas</h2>

        <div class="container">
                <h1>Nueva Cita</h1>
                <h2>Formulario de Cita</h2>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="formulario">
                        <div class="form-group">
                            <label for="cliente">Cliente</label>
                            <input type="text" name="cliente" id="cliente" required />
                        </div>

                        <div class="form-group">
                            <label for="noticia">Noticia</label>
                            <textarea name="noticia" id="noticia"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha de publicación</label>
                            <input type="date" name="fecha" id="fecha">
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto de la noticia</label>
                            <input type="file" name="fecha" id="fecha">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="button button-primary">Enviar</button>
                            <button type="reset" class="button button-secondary">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
    </body>
    <?php include 'footer.php'; ?>

</html>