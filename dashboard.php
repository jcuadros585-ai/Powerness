<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel principal - PowerNess</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="dashboard-page">

    <aside class="sidebar">
        <div class="brand-box">
            <h2>PowerNess</h2>
            <p>Panel de entrenamiento</p>
        </div>

        <nav class="sidebar-nav">
            <a href="dashboard.php" class="activo">Inicio</a>
            <a href="progreso.php">Mi progreso</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Hola, <?php echo $usuario; ?></h1>
                <p>Bienvenido de nuevo a tu panel principal.</p>
            </div>
            <div class="status-chip">Sesión activa</div>
        </div>

        <section class="welcome-banner">
            <h2>Tu entrenamiento empieza con control</h2>
            <p>
                Desde este panel puedes registrar tu progreso, revisar tus rutinas y consultar
                tu información personal dentro del sistema PowerNess.
            </p>
        </section>

        <section class="metric-grid">
            <div class="metric-card">
                <span>Progreso actual</span>
                <h3>Activo</h3>
                <p>El usuario puede registrar y visualizar su avance físico.</p>
            </div>

            <div class="metric-card">
                <span>Rutinas</span>
                <h3>3</h3>
                <p>Hipertrofia, definición y fuerza disponibles en el sistema.</p>
            </div>

            <div class="metric-card">
                <span>Dieta</span>
                <h3>Base</h3>
                <p>Espacio preparado para incorporar un módulo de alimentación.</p>
            </div>
        </section>

        <section class="content-grid">
            <div class="panel-card">
                <h2>Acciones principales</h2>
                <p>
                    Desde aquí puedes acceder rápidamente a los módulos más importantes
                    de la plataforma PowerNess.
                </p>

                <div class="quick-actions">
                    <a href="progreso.php" class="btn">Registrar progreso</a>
                    <a href="rutinas.php" class="btn">Registrar rutinas</a>
                    <a href="perfil.php" class="btn">Ver perfil</a>
                </div>
            </div>

            <div class="panel-card">
                <h3>Resumen rápido</h3>
                <p>
                    El panel principal centraliza la navegación del usuario después del login
                    y mejora la experiencia dentro de la aplicación web.
                </p>
            </div>
        </section>

    </main>
</div>

</body>
</html>