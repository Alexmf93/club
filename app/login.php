<?php
session_start();
require_once('check_sesion.php');

if(is_logged_in()){
    header('Location: index.php');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php 
    if (isset($_SESSION['error_login'])){
        echo '<p class="error">' . htmlspecialchars($_SESSION['error_login']) . '</p>';
        unset($_SESSION['error_login']);
    }
        
    ?>
    <form action="iniciarSesion.php" method="post">
        <div>
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" name="usuario">
        </div>

        <div>
        <label for="password"></label>
        <input type="password" name="password">
        </div>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>

