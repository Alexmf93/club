<?php
session_start();
require_once('check_sesion.php');

if(is_logged_in()){
    header('Location: index.php');
    exit;
}
?>
<?php include "head.php"; ?>
<body>
    <?php 
    include "menu.php";
    menu();
    ?>
    
    <div class="container2">
        <main>
            <section>
                <div class="container">
                    <h2 style="text-align: center; margin-bottom: 30px;">Acceso Socios</h2>
                    
                    <?php 
                    if (isset($_SESSION['error_login'])){
                        echo '<div class="flash-error">' . htmlspecialchars($_SESSION['error_login']) . '</div>';
                        unset($_SESSION['error_login']);
                    }
                    ?>

                    <form action="iniciarSesion.php" method="post" class="formulario" style="max-width: 400px; margin: 0 auto;">
                        <div class="form-group">
                            <label for="usuario">Nombre de usuario</label>
                            <input type="text" name="usuario" id="usuario" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" required>
                        </div>

                        <div class="form-actions" style="justify-content: center; margin-top: 20px;">
                            <button type="submit" class="button button-primary" style="width: 100%;">Entrar</button>
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
