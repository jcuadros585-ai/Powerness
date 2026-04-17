<?php
session_start();
include("../conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    echo "Acceso denegado";
    exit();
}

$admin = $_SESSION["usuario"];

/* Total de usuarios */
$sqlUsuarios = "SELECT COUNT(*) AS total FROM usuarios";
$resUsuarios = $conexion->query($sqlUsuarios);
$totalUsuarios = 0;

if ($resUsuarios && $resUsuarios->num_rows > 0) {
    $filaUsuarios = $resUsuarios->fetch_assoc();
    $totalUsuarios = $filaUsuarios["total"];
}

/* Total de registros de progreso */
$sqlRegistros = "SELECT COUNT(*) AS total FROM progreso";
$resRegistros = $conexion->query($sqlRegistros);
$totalRegistros = 0;

if ($resRegistros && $resRegistros->num_rows > 0) {
    $filaRegistros = $resRegistros->fetch_assoc();
    $totalRegistros = $filaRegistros["total"];
}

/* Total de admins */
$sqlAdmins = "SELECT COUNT(*) AS total FROM usuarios WHERE rol='admin'";
$resAdmins = $conexion->query($sqlAdmins);
$totalAdmins = 0;

if ($resAdmins && $resAdmins->num_rows > 0) {
    $filaAdmins = $resAdmins->fetch_assoc();
    $totalAdmins = $filaAdmins["total"];
}

/* Últimos usuarios registrados */
$sqlUltimosUsuarios = "SELECT nombre, correo, edad, objetivo, nivel, rol 
                       FROM usuarios 
                       ORDER BY id DESC 
                       LIMIT 5";
$ultimosUsuarios = $conexion->query($sqlUltimosUsuarios);

/* Últimos registros de progreso */
$sqlUltimosProgresos = "SELECT usuario, peso, estatura, fecha 
                        FROM progreso 
                        ORDER BY id DESC 
                        LIMIT 5";
$ultimosProgresos = $conexion->query($sqlUltimosProgresos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - PowerNess</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="dashboard-page">

    <aside class="sidebar">
        <div class="brand-box">
            <h2>PowerNess</h2>
            <p>Panel administrador</p>
        </div>

        <nav class="sidebar-nav">
            <a href="dashboard.php" class="activo">Inicio</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="dietas.php">Dietas</a>
            <a href="registros.php">Registros</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="../dashboard.php">Volver al panel</a>
            <a href="../logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Hola, <?php echo $admin; ?></h1>
                <p>Bienvenido al panel de administración de PowerNess.</p>
            </div>
            <div class="status-chip">Administrador activo</div>
        </div>

        <section class="welcome-banner">
            <h2>Gestión general del sistema</h2>
            <p>
                Desde este panel puedes supervisar la cantidad de usuarios registrados,
                revisar los progresos almacenados en la base de datos y acceder a los
                módulos de administración del sistema.
            </p>
        </section>

        <section class="metric-grid">
            <div class="metric-card">
                <span>Total de usuarios</span>
                <h3><?php echo $totalUsuarios; ?></h3>
                <p>Cantidad de cuentas registradas en la plataforma.</p>
            </div>

            <div class="metric-card">
                <span>Total de registros físicos</span>
                <h3><?php echo $totalRegistros; ?></h3>
                <p>Registros de progreso almacenados por los usuarios.</p>
            </div>

            <div class="metric-card">
                <span>Total de administradores</span>
                <h3><?php echo $totalAdmins; ?></h3>
                <p>Usuarios con permisos de administración del sistema.</p>
            </div>
        </section>

        <section class="content-grid">
            <div class="panel-card">
                <h2>Acciones rápidas</h2>
                <p>
                    Accede rápidamente a los principales módulos de gestión del sistema.
                </p>

                <div class="quick-actions">
                    <a href="usuarios.php" class="btn">Gestionar usuarios</a>
                    <a href="dietas.php" class="btn">Gestionar dietas</a>
                    <a href="registros.php" class="btn">Ver registros</a>
                    <a href="rutinas.php" class="btn">Gestionar rutinas</a>
                </div>
            </div>

            <div class="panel-card">
                <h3>Resumen administrativo</h3>
                <p>
                    El panel administrador permite centralizar la supervisión del sistema,
                    facilitando el control de usuarios, información de progreso y futuras
                    actualizaciones de contenido dentro de la plataforma.
                </p>
            </div>
        </section>

        <section class="double-panel">
            <div class="panel-card">
                <h2>Últimos usuarios registrados</h2>

                <div class="table-wrap">
                    <table>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Edad</th>
                            <th>Objetivo</th>
                            <th>Nivel</th>
                            <th>Rol</th>
                        </tr>

                        <?php if ($ultimosUsuarios && $ultimosUsuarios->num_rows > 0) { ?>
                            <?php while ($fila = $ultimosUsuarios->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $fila["nombre"]; ?></td>
                                    <td><?php echo $fila["correo"]; ?></td>
                                    <td><?php echo $fila["edad"]; ?></td>
                                    <td><?php echo $fila["objetivo"]; ?></td>
                                    <td><?php echo $fila["nivel"]; ?></td>
                                    <td><?php echo $fila["rol"]; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="6">No hay usuarios registrados.</td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <div class="panel-card">
                <h2>Últimos registros de progreso</h2>

                <div class="table-wrap">
                    <table>
                        <tr>
                            <th>Usuario</th>
                            <th>Peso</th>
                            <th>Estatura</th>
                            <th>Fecha</th>
                        </tr>

                        <?php if ($ultimosProgresos && $ultimosProgresos->num_rows > 0) { ?>
                            <?php while ($fila = $ultimosProgresos->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $fila["usuario"]; ?></td>
                                    <td><?php echo $fila["peso"]; ?> kg</td>
                                    <td><?php echo $fila["estatura"]; ?> m</td>
                                    <td><?php echo $fila["fecha"]; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4">No hay registros de progreso.</td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </section>

    </main>
</div>

</body>
</html>