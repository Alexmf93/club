<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container2">
        <?php 
        include "menu.php";
        menu();
        ?>
<main>
     <section id="admin-citas">
        <div class="container">
            <h2>Gestión de Citas</h2>
            <form action="" method="post" enctype="multipart/form-data" id="citaForm">
                <div class="formulario">
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <select name="cliente" id="cliente" required>
                            <option value="">-- Seleccionar cliente --</option>
                            <option value="1">Juan Pérez</option>
                            <option value="2">María García</option>
                            <option value="3">Carlos López</option>
                        </select>
                        <span id="clienteError" class="error"></span>
                    </div>
                    <div class="form-group">
                        <label for="servicio">Servicio</label>
                        <select name="servicio" id="servicio" required>
                            <option value="">-- Seleccionar servicio --</option>
                            <option value="1">Clase de spinning</option>
                            <option value="2">Sauna</option>
                            <option value="3">Solarium</option>
                        </select>
                        <span id="servicioError" class="error"></span>
                    </div>
                    <div class="form-group">
                        <label for="fechaCita">Fecha</label>
                        <input type="date" name="fechaCita" id="fechaCita"/>
                        <span id="fechaCitaError" class="error"></span>
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
    <script src="js/jsCita.js"></script>  
</body>
</html>