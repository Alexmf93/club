# Documentación: Gestión de Usuarios y Permisos

## 📋 Descripción General

Este documento detalla cómo se implementa el sistema de autenticación y control de permisos en la aplicación web del club. El sistema está basado en roles y permite diferentes niveles de acceso según el tipo de usuario.

---

## 🔐 Sistema de Autenticación

### 1. **Tabla de Usuarios en Base de Datos**

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE,
    edad INT,
    clave VARCHAR(255) NOT NULL,           -- Contraseña hasheada con bcrypt
    rol ENUM('socio','administrador', 'normal') NOT NULL DEFAULT 'socio',
    telefono VARCHAR(20) UNIQUE,
    foto VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Campos principales:**
- `nombre`: Nombre único del usuario (identificador de login)
- `clave`: Contraseña hasheada con bcrypt (`password_hash()`)
- `rol`: Define el nivel de permisos (3 tipos)
- `telefono`: Contacto del usuario
- `foto`: Imagen de perfil almacenada en carpeta `uploads/`
- `fecha_registro`: Timestamp de cuándo se registró

---

## 👥 Roles de Usuario y Permisos

### **1. Administrador (rol = 'administrador')**

| Funcionalidad | Permiso |
|---|---|
| Gestionar socios | ✅ **Acceso total** |
| Ver lista de todos los socios | ✅ |
| Crear/Editar/Eliminar socios | ✅ |
| Modificar rol de usuarios | ✅ |
| Crear noticias | ✅ **Acceso total** |
| Editar noticias | ✅ |
| Eliminar noticias | ✅ |
| Ver todas las citas | ✅ |
| Gestionar citas de cualquier usuario | ✅ |
| Crear servicios | ✅ |
| Editar servicios | ✅ |
| Ver testimonios | ✅ |
| Acceder a todas las funciones | ✅ |

**Acceso:** [socio.php](app/socio.php#L4) - Línea 4
```php
if(!(isset($_SESSION['rol']) && $_SESSION['rol'] == 'administrador')){
    header("Location: index.php");
    exit;
}
```

---

### **2. Socio (rol = 'socio')**

| Funcionalidad | Permiso |
|---|---|
| Gestionar socios | ❌ |
| Ver lista de noticias | ✅ |
| Crear nuevas noticias | ✅ **Acceso parcial** |
| Ver citas personales | ✅ **Solo sus citas** |
| Crear citas | ✅ |
| Editar citas propias | ✅ |
| Eliminar citas propias | ✅ |
| Ver servicios disponibles | ✅ |
| Ver testimonios | ✅ |
| Acceso limitado al sistema | ✅ |

**Acceso:** [cita.php](app/cita.php#L4) - Líneas 4-7
```php
if(!(isset($_SESSION['rol']) && $_SESSION['rol'] == 'socio' || $_SESSION['rol'] == 'administrador')){
    header("Location: index.php");
    exit;
}
```

**Restricción en citas:**
- Socios solo ven SUS citas
- Administradores ven todas las citas
```php
// Si no es administrador, filtrar solo sus citas
if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'administrador') {
    $sql_citas .= " AND c.id_socio = :id_usuario";
    $params_citas[':id_usuario'] = $_SESSION['user_id'];
}
```

---

### **3. Usuario Normal (rol = 'normal')**

| Funcionalidad | Permiso |
|---|---|
| Ver noticias públicas | ✅ |
| Ver servicios | ✅ |
| Ver testimonios | ✅ |
| Acceso restringido | ❌ |
| Crear contenido | ❌ |
| Gestionar usuarios | ❌ |

**Nota:** Los usuarios sin rol (no logueados) pueden ver solo contenido público.

---

## 🔑 Flujo de Login/Logout

### **1. Proceso de Login**

**Archivo:** [iniciarSesion.php](app/iniciarSesion.php)

```php
// 1. Validar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// 2. Obtener datos del formulario
$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

// 3. Preparar consulta segura (prepared statements)
$sql = "SELECT id, nombre, rol, clave FROM usuarios WHERE nombre = :usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
$stmt->execute();

// 4. Obtener usuario
$user = $stmt->fetch();

// 5. Verificar contraseña con bcrypt
if ($user && password_verify($password, $user['clave'])) {
    // LOGIN CORRECTO
    session_regenerate_id(true);  // Seguridad: regenerar ID de sesión
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['nombre'];
    $_SESSION['rol'] = $user['rol'];
    
    header('Location: index.php');
    exit;
} else {
    // LOGIN INCORRECTO
    $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
    header('Location: login.php');
    exit;
}
```

**Variables de sesión creadas:**
- `$_SESSION['user_id']`: ID del usuario
- `$_SESSION['username']`: Nombre del usuario
- `$_SESSION['rol']`: Rol del usuario (administrador/socio/normal)

---

### **2. Proceso de Logout**

**Archivo:** [logout.php](app/logout.php)

```php
<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redirigir al inicio
header('Location: index.php');
exit();
?>
```

---

## 🛡️ Funciones de Seguridad

### **Validación de Sesión**

**Archivo:** [check_sesion.php](app/check_sesion.php)

```php
<?php
function is_logged_in(){
    return isset($_SESSION['username']);
}

function require_login(){
    if(!is_logged_in()){
        header('Location: login.php');
        exit();
    }
}
?>
```

**Uso en páginas protegidas:**
- Se incluye en cada página que requiere autenticación
- `is_logged_in()`: Verifica si el usuario está logueado
- `require_login()`: Redirige a login si no está autenticado

---

## 🎨 Menú Dinámico según Rol

**Archivo:** [menu.php](app/menu.php)

El menú se adapta dinámicamente según el rol del usuario:

```php
<!-- Solo administrador ve la opción de Socios -->
<?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'administrador'): ?>
    <a href="socio.php">Socios</a>
<?php endif; ?>

<!-- Solo usuarios logueados ven Citas -->
<?php if(isset($_SESSION['rol'])): ?>
    <a href="cita.php">Citas</a>
<?php endif; ?>

<!-- Solo administrador ve el formulario de crear noticias -->
<?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'administrador'): ?>
    <!-- Formulario de crear noticias -->
<?php endif; ?>
```

**Menú de usuario autenticado:**
```php
<?php if(isset($_SESSION['username'])): ?>
    <!-- Usuario logueado -->
    <div class="user-menu">
        <button class="user-button" id="userMenuBtn">
            <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </button>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="perfil.php">Ver perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
<?php else: ?>
    <!-- Usuario no logueado -->
    <a href="login.php" class="login-link">Iniciar sesión</a>
<?php endif; ?>
```

---

## 📄 Archivos Protegidos por Rol

| Archivo | Rol Requerido | Descripción |
|---|---|---|
| [socio.php](app/socio.php) | Administrador | Gestión completa de socios |
| [cita.php](app/cita.php) | Socio/Administrador | Calendario y gestión de citas |
| [noticia.php](app/noticia.php) | Administrador | Crear/editar noticias |
| [servicio.php](app/servicio.php) | Posible acceso | Gestión de servicios |
| [testimonio.php](app/testimonio.php) | Socio/Administrador | Ver y crear testimonios |
| [perfil.php](app/perfil.php) | Logueado | Ver perfil personal |

---

## ⚙️ Flujo de Protección en Archivos de Procesamiento

### **Ejemplo: procesar_cita.php**

```php
// El archivo procesa peticiones de crear/editar/eliminar citas
// y verifica:

1. if ($action === 'delete') {
    // Solo el propietario o administrador puede eliminar
    if ($cita['id_socio'] != $_SESSION['user_id'] && $_SESSION['rol'] !== 'administrador') {
        die("❌ No tienes permiso para eliminar esta cita.");
    }
}

2. if ($action === 'update') {
    // Solo el propietario o administrador puede editar
    if ($cita['id_socio'] != $_SESSION['user_id'] && $_SESSION['rol'] !== 'administrador') {
        die("❌ No tienes permiso para editar esta cita.");
    }
}
```

---

## 🔒 Medidas de Seguridad Implementadas

1. **Hashing de Contraseñas**
   - Se usa `password_hash()` con bcrypt
   - Verificación con `password_verify()`

2. **Prepared Statements**
   - Protección contra inyección SQL
   - Todas las consultas usan parámetros

3. **Regeneración de ID de Sesión**
   - Al hacer login: `session_regenerate_id(true)`
   - Previene ataques de fijación de sesión

4. **Validación de Rol en Backend**
   - No confiar solo en validación de cliente
   - Verificación en servidor antes de cada operación

5. **Sanitización de Salida**
   - Uso de `htmlspecialchars()` para prevenir XSS
   - Validación de datos en formularios

6. **Control de Acceso**
   - Verificación de rol antes de acceder a archivos
   - Redirección a index.php si no hay permisos

---

## 📊 Estructura de Sesión

```php
$_SESSION = [
    'user_id'   => 4,                    // ID del usuario en BD
    'username'  => 'Alejandro Buendía',  // Nombre único
    'rol'       => 'socio',              // administrador|socio|normal
    'error_login' => ''                  // Mensajes de error (temporal)
];
```

---

## ✅ Datos de Prueba

**Usuarios por defecto en la BD:**

| Usuario | Contraseña | Rol | Uso |
|---|---|---|---|
| Juan García | 1234 | administrador | Acceso total al sistema |
| María García | 1234 | socio | Acceso limitado a citas/servicios |
| Carlos pepe | 1234 | socio | Acceso limitado a citas/servicios |
| Alejandro Buendía | (bcrypt hash) | socio | Usuario ejemplo con citas |
| David Lara | (bcrypt hash) | socio | Usuario ejemplo con servicios |

**Nota:** Las contraseñas en texto plano deben reemplazarse con hashes bcrypt en producción.

---

## 🚀 Resumen de Permisos por Funcionalidad

```
┌─────────────────────────────────────────────────────────────────┐
│                     MATRIZ DE PERMISOS                          │
├──────────────────────┬───────────┬────────┬──────────┐
│ Funcionalidad        │ Admin     │ Socio  │ Normal   │
├──────────────────────┼───────────┼────────┼──────────┤
│ Ver Noticias         │ ✅        │ ✅     │ ✅       │
│ Crear Noticias       │ ✅        │ ✅     │ ❌       │
│ Editar Noticias      │ ✅        │ ❌     │ ❌       │
│ Ver Citas            │ ✅ Todas  │ ✅ Propias │ ❌    │
│ Crear Citas          │ ✅        │ ✅     │ ❌       │
│ Editar Citas         │ ✅        │ ✅*    │ ❌       │
│ Eliminar Citas       │ ✅        │ ✅*    │ ❌       │
│ Gestionar Socios     │ ✅        │ ❌     │ ❌       │
│ Ver Servicios        │ ✅        │ ✅     │ ✅       │
│ Crear Servicios      │ ✅        │ ❌     │ ❌       │
│ Ver Testimonios      │ ✅        │ ✅     │ ✅       │
│ Crear Testimonios    │ ✅        │ ✅     │ ❌       │
└──────────────────────┴───────────┴────────┴──────────┘
(*) Solo sus propias citas
```

---

## 📝 Conclusión

El sistema implementa un control de acceso basado en roles (RBAC) que:
- ✅ Protege funcionalidades críticas con validación de rol
- ✅ Usa hashing seguro para contraseñas
- ✅ Implementa prepared statements contra inyección SQL
- ✅ Adapta la UI según permisos del usuario
- ✅ Mantiene auditoría básica con timestamps

**Recomendaciones para mejora:**
1. Implementar logs de auditoría más detallados
2. Añadir tokens CSRF en formularios
3. Implementar autenticación de dos factores (2FA)
4. Usar HTTPS en producción
5. Implementar rate limiting para login
