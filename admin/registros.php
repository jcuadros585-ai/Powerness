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

/* Eliminar registro */
if (isset($_GET["eliminar"])) {
    $idEliminar = (int) $_GET["eliminar"];
    $sqlEliminar = "DELETE FROM progreso WHERE id = $idEliminar";
    $conexion->query($sqlEliminar);
    header("Location: registros.php");
    exit();
}

/* Obtener todos los registros */
$sqlRegistros = "SELECT * FROM progreso ORDER BY fecha DESC, id DESC";
$registros = $conexion->query($sqlRegistros);

/* Total de registros */
$sqlTotal = "SELECT COUNT(*) AS total FROM progreso";
$resTotal = $conexion->query($sqlTotal);
$totalRegistros = 0;

if ($resTotal && $resTotal->num_rows > 0) {
    $filaTotal = $resTotal->fetch_assoc();
    $totalRegistros = $filaTotal["total"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros - Panel Admin</title>
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
            <a href="dashboard.php">Inicio</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="dietas.php">Dietas</a>
            <a href="registros.php" class="activo">Registros</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="../dashboard.php">Volver al panel</a>
            <a href="../logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Gestión de registros</h1>
                <p>Supervisa los datos de progreso físico registrados por los usuarios.</p>
            </div>
            <div class="status-chip">Módulo activo</div>
        </div>

        <section class="welcome-banner">
            <h2>Seguimiento general del sistema</h2>
            <p>
                En este módulo se visualizan todos los registros físicos almacenados en la base de datos,
                permitiendo revisar la evolución reportada por los usuarios y mantener un mejor control del sistema.
            </p>
        </section>

        <section class="metric-grid">
            <div class="metric-card">
                <span>Total de registros</span>
                <h3><?php echo $totalRegistros; ?></h3>
                <p>Cantidad de registros de progreso almacenados en la plataforma.</p>
            </div>

            <div class="metric-card">
                <span>Gestión centralizada</span>
                <h3>Activa</h3>
                <p>El administrador puede supervisar todos los avances registrados.</p>
            </div>

            <div class="metric-card">
                <span>Control del sistema</span>
                <h3>Completo</h3>
                <p>Visualización global de datos físicos generados por los usuarios.</p>
            </div>
        </section>

        <section class="panel-card">
            <h2>Lista general de registros</h2>

            <div class="table-wrap">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Peso</th>
                        <th>Estatura</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>

                    <?php if ($registros && $registros->num_rows > 0) { ?>
                        <?php while ($fila = $registros->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $fila["id"]; ?></td>
                                <td><?php echo $fila["usuario"]; ?></td>
                                <td><?php echo $fila["peso"]; ?> kg</td>
                                <td><?php echo $fila["estatura"]; ?> m</td>
                                <td><?php echo $fila["fecha"]; ?></td>
                                <td>
                                    <a href="registros.php?eliminar=<?php echo $fila["id"]; ?>" class="btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este registro?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6">No hay registros disponibles.</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </section>

    </main>
</div>

</body>
</html>