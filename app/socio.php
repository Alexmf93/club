<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Socio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php 
    include "menu.php";
    menu();
    ?>
    <div class="container2">
        <main>
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
    <script src="js/jsSocio.js"></script>
</body>
</html>