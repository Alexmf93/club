<?php
function pie() {
    ?>
    <footer style="
        background: #111;
        color: #f2f2f2;
        padding: 25px 0;
        margin-top: 40px;
        text-align: center;
        font-family: Arial, sans-serif;
    ">
        <div style="max-width: 900px; margin: auto;">
            <p style="margin: 0; font-size: 16px;">
                © <?= date("Y"); ?> - Club Deportivo El Real
            </p>
            <p style="margin: 6px 0 0; font-size: 14px; opacity: 0.8;">
                Todos los derechos reservados
            </p>

            <nav style="margin-top: 12px;">
                <a href="paginaPrincipal.php" style="color: #fff; margin: 0 10px; text-decoration: none;">Inicio</a>
                <a href="contacto.php" style="color: #fff; margin: 0 10px; text-decoration: none;">Contacto</a>
                <a href="politica.php" style="color: #fff; margin: 0 10px; text-decoration: none;">Política de Privacidad</a>
            </nav>
        </div>
    </footer>
    <?php
}
?>
