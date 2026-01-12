<?php
session_start();
require_once 'db_config.php';
require_once 'check_sesion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    include "menu.php";
    menu();

    // Mostrar mensajes de éxito o error
    if (!empty($_GET['msg'])) {
        echo '<div class="flash-success">' . htmlspecialchars($_GET['msg']) . '</div>';
    } elseif (!empty($_GET['error'])) {
        echo '<div class="flash-error">' . htmlspecialchars($_GET['error']) . '</div>';
    }
    ?>
    <div class="container2">
        <main>
            <section id="perfil-usuario">
                <div class="container">
                    <h2>Mi Perfil</h2>
                    <?php
                    // Cargar datos del usuario logueado usando la sesión
                    $socioId = $_SESSION['user_id'];
                    $socioData = null;

                    $stmtSocio = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
                    $stmtSocio->execute([':id' => $socioId]);
                    $socioData = $stmtSocio->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <form action="procesar_socio.php" method="post" enctype="multipart/form-data" id="formularioPerfil">
                        <!-- Campo oculto para indicar redirección personalizada -->
                        <input type="hidden" name="redirect_url" value="perfil.php">
                        
                        <div class="formulario">
                            <?php if ($socioData): ?>
                                <input type="hidden" name="id" value="<?php echo $socioData['id']; ?>">
                            <?php endif; ?>
                            <div class="form-group-columns">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" name="nombre" id="nombre" placeholder="Ingresa tu nombre" value="<?php echo $socioData ? htmlspecialchars($socioData['nombre']) : ''; ?>"/>
                                    <span id="nombreError" class="error"></span>
                                </div>
                              
                            
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" name="password" id="password" placeholder="<?php echo $socioData ? 'Dejar en blanco para no cambiar' : ''; ?>"/>
                                    <span id="contraseñaError" class="error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" name="telefono" id="telefono" value="<?php echo $socioData ? $socioData['telefono'] : ''; ?>"/>
                                <span id="telefonoError" class="error"></span>
                            </div>
                            <div class="form-group">
                                <label for="foto">Foto del socio</label>
                                <input type="file" name="foto" id="foto"/>
                                <span id="fotoError" class="error"></span>
                                <?php if ($socioData && $socioData['foto']): ?>
                                    <p><small>Foto actual: <img src="<?php echo $socioData['foto']; ?>" style="max-width:100px;"></small></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="button button-primary">Guardar Cambios</button>
                                <a href='index.php' class='button button-secondary'>Cancelar</a>
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
</body>
</html>