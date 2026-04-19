<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];

$sqlUsuario = "SELECT * FROM usuarios WHERE nombre='$usuario' LIMIT 1";
$resUsuario = $conexion->query($sqlUsuario);
$datosUsuario = $resUsuario->fetch_assoc();
$usuarioId = (int)$datosUsuario["id"];

/* Marcar como leidas */
if (isset($_GET["leer"])) {
    $conexion->query("UPDATE notificaciones SET leida = 1 WHERE usuario_id = $usuarioId");
    header("Location: notificaciones.php");
    exit();
}

$sqlNotis = "SELECT * FROM notificaciones WHERE usuario_id = $usuarioId ORDER BY fecha DESC";
$notis = $conexion->query($sqlNotis);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - PowerNess</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="dashboard-page">
    <aside class="sidebar">
        <div class="brand-box">
            <h2>PowerNess</h2>
            <p>Panel de entrenamiento</p>
        </div>

        <nav class="sidebar-nav">
            <a href="dashboard.php">Inicio</a>
            <a href="progreso.php">Mi progreso</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="dieta.php">Dieta</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="chat.php">Chat</a>
            <a href="notificaciones.php" class="activo">Notificaciones</a>
            <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin") { ?>
                <a href="admin/dashboard.php">Panel Admin</a>
            <?php } ?>
            <a href="logout.php">Cerrar sesion</a>
        </nav>
    </aside>

    <main class="dashboard-main">
        <div class="topbar">
            <div class="topbar-left">
                <h1>Notificaciones</h1>
                <p>Revisa tus avisos y actualizaciones importantes.</p>
            </div>
            <a class="btn" href="notificaciones.php?leer=1">Marcar como leidas</a>
        </div>

        <section class="panel-card">
            <h2>Bandeja</h2>
            <div class="notification-list">
                <?php if ($notis && $notis->num_rows > 0) { ?>
                    <?php while ($fila = $notis->fetch_assoc()) { ?>
                        <div class="notification-item <?php echo $fila["leida"] == 0 ? "notification-unread" : ""; ?>">
                            <h3><?php echo htmlspecialchars($fila["titulo"]); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($fila["mensaje"])); ?></p>
                            <small><?php echo $fila["fecha"]; ?></small>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No tienes notificaciones por el momento.</p>
                <?php } ?>
            </div>
        </section>
    </main>
</div>

</body>
</html>