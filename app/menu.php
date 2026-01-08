<?php
require_once 'check_sesion.php';
function menu() { ?>
    <nav class="menu">
        <!-- Menú principal de la web -->
        <a href="paginaPrincipal.php">Inicio</a>
        <a href="paginaPrincipal.php#noticias">Noticias destacadas</a>
        <a href="paginaPrincipal.php#equipos">Nuestros servicios</a>
        <!-- Gestión interna -->
        <a href="testimonio.php">Testimonios</a>
         <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'administrador'): ?>
        <a href="socio.php">Socios</a>
        <?php endif; ?>
        <a href="servicio.php">Servicios</a>
        <a href="noticia.php">Gestión Noticias</a>
        <a href="cita.php">Citas</a>
     

        <div class="auth-section">
            <?php if(isset($_SESSION['username'])): ?>
                <!-- Usuario logueado -->
                <div class="user-menu">
                    <button class="user-button" id="userMenuBtn">
                        <span class="user-icon">👤</span>
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <span class="arrow">▼</span>
                    </button>
                    
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="perfil.php" class="dropdown-item">
                            <span class="icon">⚙️</span> Ver perfil
                        </a>
                        <a href="logout.php" class="dropdown-item">
                            <span class="icon">🚪</span> Cerrar sesión
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Usuario no logueado -->
                <a href="login.php" class="login-link">Iniciar sesión</a>
            <?php endif; ?>
        </div>
        
    </nav>
<script>
    // Toggle del menú desplegable
    const userMenuBtn = document.getElementById('userMenuBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    if(userMenuBtn) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        // Cerrar el menú al hacer click fuera
        document.addEventListener('click', function(e) {
            if(!userMenuBtn.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
</script>
<?php } ?>
