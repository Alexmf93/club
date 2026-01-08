<?php
session_start();
require_once 'db_config.php';
if(!(isset($_SESSION['rol']) && $_SESSION['rol'] == 'socio' || $_SESSION['rol'] == 'administrador')){
    header("Location: paginaPrincipal.php");
    exit;

}

// Mostrar banner (flash) si hay mensaje en la query string
if (!empty($_GET['msg'])) {
    echo '<div class="flash-success">' . htmlspecialchars($_GET['msg']) . '</div>';
} elseif (!empty($_GET['error'])) {
    echo '<div class="flash-error">' . htmlspecialchars($_GET['error']) . '</div>';
}

require_once 'db_config.php';

// Conexión global
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<p class="error">❌ Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

// Obtener mes y año actual o desde parámetro GET
$mes = isset($_GET['mes']) && is_numeric($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$año = isset($_GET['año']) && is_numeric($_GET['año']) ? (int)$_GET['año'] : (int)date('Y');

// Validar rango de mes
if ($mes < 1) { $mes = 12; $año--; }
if ($mes > 12) { $mes = 1; $año++; }

$fecha_inicio = "$año-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-01";
$fecha_fin = date('Y-m-t', strtotime($fecha_inicio));

// Obtener citas del mes
$sql_citas = "SELECT c.*, u.nombre as socio, u.telefono, s.descripcion as servicio 
              FROM citas c 
              JOIN usuarios u ON c.id_socio = u.id 
              JOIN servicios s ON c.id_servicio = s.id 
              WHERE DATE(c.fecha_cita) BETWEEN :inicio AND :fin
              ";

$params_citas = [':inicio' => $fecha_inicio, ':fin' => $fecha_fin];

// Si no es administrador, filtrar solo sus citas
if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'administrador') {
    $sql_citas .= " AND c.id_socio = :id_usuario";
    $params_citas[':id_usuario'] = $_SESSION['user_id'];
}

$sql_citas .= " ORDER BY c.fecha_cita ASC";

$stmt_citas = $pdo->prepare($sql_citas);
$stmt_citas->execute($params_citas);
$citas_mes = $stmt_citas->fetchAll(PDO::FETCH_ASSOC);

// Agrupar citas por fecha
$citas_agrupadas = [];
foreach ($citas_mes as $cita) {
    $fecha_key = substr($cita['fecha_cita'], 0, 10);
    if (!isset($citas_agrupadas[$fecha_key])) {
        $citas_agrupadas[$fecha_key] = [];
    }
    $citas_agrupadas[$fecha_key][] = $cita;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .calendar {
            width: 100%;
            margin: 1rem 0;
            border-collapse: collapse;
        }
        .calendar th, .calendar td {
            border: 1px solid #444;
            padding: 0.5rem;
            text-align: center;
            height: 80px;
            vertical-align: top;
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        .calendar th {
            background-color: #ff6b6b;
            color: white;
            font-weight: bold;
        }
        .calendar td {
            position: relative;
            cursor: pointer;
        }
        .calendar td:hover {
            background-color: #252525;
        }
        .calendar .day-number {
            font-weight: bold;
            margin-bottom: 0.3rem;
        }
        .calendar .cita-item {
            font-size: 0.75rem;
            background-color: #ff6b6b;
            color: white;
            padding: 0.2rem;
            margin: 0.1rem 0;
            border-radius: 3px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .calendar .otros-meses {
            color: #666;
        }
        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .calendar-nav button, .calendar-nav a {
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
<?php include "menu.php"; menu(); ?>


<?php if(isset($_SESSION['username'])): ?>
<div class="container2">
    <main>
        <!-- CALENDARIO -->
        <section id="calendario-citas">
            <div class="container">
                <h2>Calendario de Citas</h2>
                
                <div class="calendar-nav">
                    <a href="cita.php?mes=<?php echo $mes - 1; ?>&año=<?php echo $año; ?>" class="button button-secondary">← Mes Anterior</a>
                    <span style="font-size: 1.2rem; font-weight: bold;">
                        <?php 
                        $meses_es = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                        echo $meses_es[$mes - 1] . ' ' . $año; 
                        ?>
                    </span>
                    <a href="cita.php?mes=<?php echo $mes + 1; ?>&año=<?php echo $año; ?>" class="button button-secondary">Mes Siguiente →</a>
                </div>

<?php
// Generar tabla de calendario
$primer_dia = new DateTime("$año-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-01");
$dias_en_mes = (int)$primer_dia->format('t');
$dia_semana_inicio = (int)$primer_dia->format('w');
if ($dia_semana_inicio === 0) $dia_semana_inicio = 7; // Domingo -> 7

echo '<table class="calendar">
    <thead>
        <tr>
            <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
        </tr>
    </thead>
    <tbody>';

$dia = 1;
for ($semana = 0; $semana < 6; $semana++) {
    echo '<tr>';
    for ($dow = 1; $dow <= 7; $dow++) {
        if (($semana === 0 && $dow < $dia_semana_inicio) || $dia > $dias_en_mes) {
            echo '<td class="otros-meses"></td>';
        } else {
            $fecha_actual = "$año-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
            $tiene_citas = isset($citas_agrupadas[$fecha_actual]);
            echo '<td ' . ($tiene_citas ? 'class="tiene-citas"' : '') . '>';
            echo '<div class="day-number">' . $dia . '</div>';
            if ($tiene_citas) {
                foreach (array_slice($citas_agrupadas[$fecha_actual], 0, 3) as $c) {
                    echo '<div class="cita-item" title="' . htmlspecialchars($c['socio'] . ' - ' . $c['servicio'] . ' - ' . substr($c['fecha_cita'], 11, 5)) . '">';
                    echo htmlspecialchars(substr($c['fecha_cita'], 11, 5) . ' ' . substr($c['socio'], 0, 8));
                    echo '</div>';
                }
                if (count($citas_agrupadas[$fecha_actual]) > 3) {
                    echo '<div class="cita-item">+' . (count($citas_agrupadas[$fecha_actual]) - 3) . ' más</div>';
                }
            }
            echo '</td>';
            $dia++;
        }
    }
    echo '</tr>';
}
echo '</tbody></table>';
?>
   <?php endif; ?>

            </div>
        </section>

        <!-- BUSCADOR DE CITAS -->

        <section id="buscador-citas">
            <div class="container">
                <h2>Buscar Citas</h2>
                <form method="get" action="cita.php" id="formBuscador" class="search-form">
                    <input type="hidden" name="mes" value="<?php echo $mes; ?>">
                    <input type="hidden" name="año" value="<?php echo $año; ?>">
                     <input type="text" name="buscar_socio" id="buscar_socio" placeholder="Buscar socio..." value="<?php echo isset($_GET['buscar_socio']) ? htmlspecialchars($_GET['buscar_socio']) : (isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''); ?>">
                    <input type="hidden"name="id_autor" id="id_autor" value="<?php echo $_SESSION['user_id'] ?>">
                    <select name="buscar_servicio">
                        <option value="">-- Todos los servicios --</option>
<?php
$stmtServ = $pdo->query("SELECT id, descripcion FROM servicios ORDER BY descripcion ASC");
while ($srv = $stmtServ->fetch(PDO::FETCH_ASSOC)) {
    $selected = (isset($_GET['buscar_servicio']) && $_GET['buscar_servicio'] == $srv['username']) ? 'selected' : '';
    echo '<option value="' . $srv['id'] . '" ' . $selected . '>' . htmlspecialchars($srv['descripcion']) . '</option>';
}
?>
                    </select>
                    <button type="submit" class="button button-primary">Buscar</button>
                    <a href="cita.php" class="button button-secondary">Limpiar</a>
                </form>

                <h3>Resultados de Búsqueda</h3>
<?php
$sql_busqueda = "SELECT c.*, u.nombre as socio, u.telefono, s.descripcion as servicio 
                 FROM citas c 
                 JOIN usuarios u ON c.id_socio = u.id 
                 JOIN servicios s ON c.id_servicio = s.id 
                 WHERE 1=1";
$params = [];

// Si no es administrador, filtrar solo sus citas en la búsqueda
if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'administrador') {
    $sql_busqueda .= " AND c.id_socio = :id_usuario";
    $params[':id_usuario'] = $_SESSION['user_id'];
}

if (!empty($_GET['buscar_socio'])) {
    $sql_busqueda .= " AND u.nombre LIKE :socio";
    $params[':socio'] = '%' . $_GET['buscar_socio'] . '%';
}

if (!empty($_GET['buscar_fecha'])) {
    $sql_busqueda .= " AND DATE(c.fecha_cita) = :fecha";
    $params[':fecha'] = $_GET['buscar_fecha'];
}

if (!empty($_GET['buscar_servicio'])) {
    $sql_busqueda .= " AND c.id_servicio = :servicio";
    $params[':servicio'] = (int)$_GET['buscar_servicio'];
}

$sql_busqueda .= " ORDER BY c.fecha_cita DESC";

$stmt_busqueda = $pdo->prepare($sql_busqueda);
if ($params) $stmt_busqueda->execute($params);
else $stmt_busqueda = $pdo->query($sql_busqueda);

$resultados = $stmt_busqueda->fetchAll(PDO::FETCH_ASSOC);

if ($resultados) {
    echo '<table style="width:100%; border-collapse: collapse; margin-top: 1rem;">';
    echo '<tr style="background-color: #ff6b6b; color: white;"><th>Socio</th><th>Teléfono</th><th>Servicio</th><th>Fecha y Hora</th><th>Acciones</th></tr>';
    
    $hoy = date('Y-m-d');
    foreach ($resultados as $cita) {
        $fecha_cita = substr($cita['fecha_cita'], 0, 10);
        $puede_borrar = ($fecha_cita > $hoy) ? 1 : 0;
        
        echo '<tr style="border-bottom: 1px solid #444; padding: 0.5rem;">';
        echo '<td style="padding: 0.5rem;">' . htmlspecialchars($cita['socio']) . '</td>';
        echo '<td style="padding: 0.5rem;">' . htmlspecialchars($cita['telefono']) . '</td>';
        echo '<td style="padding: 0.5rem;">' . htmlspecialchars($cita['servicio']) . '</td>';
        echo '<td style="padding: 0.5rem;">' . htmlspecialchars($cita['fecha_cita']) . '</td>';
        echo '<td style="padding: 0.5rem;">';
        if ($puede_borrar) {
            echo '<a href="eliminar_cita.php?id=' . $cita['id'] . '" class="button button-small" onclick="return confirm(\'¿Seguro que deseas eliminar esta cita?\');">Borrar</a>';
        } else {
            echo '<span style="color: #999;">No se puede borrar</span>';
        }
        echo '</td></tr>';
    }
    echo '</table>';
} else if (!empty($_GET['buscar_socio']) || !empty($_GET['buscar_fecha']) || !empty($_GET['buscar_servicio'])) {
    echo '<p>No se encontraron citas con los criterios de búsqueda.</p>';
}
?>
            </div>
        </section>

        <!-- FORMULARIO INSERTAR CITA -->
        <section id="admin-citas">
            <div class="container">
                <h2>Insertar Nueva Cita</h2>
                <form action="procesar_cita.php" method="post" id="citaForm">
                    <div class="formulario">
                        <div class="form-group">
                            <label for="id_socio">Socio</label>
                            <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                            <input type="hidden" name="id_socio" id="id_socio" value="<?php echo $_SESSION['user_id']; ?>">
                            <span id="id_socioError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="id_servicio">Servicio</label>
                            <select name="id_servicio" id="id_servicio">
                                <option value="">-- Seleccionar servicio --</option>
<?php
$stmtServ = $pdo->query("SELECT id, descripcion, duracion FROM servicios ORDER BY descripcion ASC");
while ($srv = $stmtServ->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="' . $srv['id'] . '">' . htmlspecialchars($srv['descripcion']) . ' (' . $srv['duracion'] . ' min)</option>';
}
?>
                            </select>
                            <span id="id_servicioError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="fecha_cita">Fecha</label>
                            <input type="date" name="fecha_cita" id="fecha_cita"/>
                            <span id="fecha_citaError" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="hora_cita">Hora</label>
                            <input type="time" name="hora_cita" id="hora_cita"/>
                            <span id="hora_citaError" class="error"></span>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="button button-primary">Enviar</button>
                            <button type="reset" class="button button-secondary">Cancelar</button>
                            <a href="paginaPrincipal.php" class="button button-secondary">Atrás</a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

<?php include "footer.php"; pie(); ?>
<script src="js/jsCita.js"></script>
</body>
</html>