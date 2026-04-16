<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$nombre = $_SESSION["usuario"];
$sql = "SELECT * FROM usuarios WHERE nombre='$nombre'";
$resultado = $conexion->query($sql);
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil - PowerNess</title>
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
            <a href="dashboard.php">Inicio</a>
            <a href="progreso.php">Mi progreso</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="dieta.php">Dieta</a>
            <a href="perfil.php" class="activo">Mi perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">
        <div class="topbar">
            <div class="topbar-left">
                <h1>Mi perfil</h1>
                <p>Información general del usuario registrado en la plataforma.</p>
            </div>
            <div class="status-chip">Cuenta activa</div>
        </div>

        <section class="content-grid">
            <div class="panel-card">
                <h2>Datos personales</h2>
                <p><strong>Nombre:</strong> <?php echo $usuario["nombre"]; ?></p>
                <p><strong>Correo:</strong> <?php echo $usuario["correo"]; ?></p>
                <p><strong>Edad:</strong> <?php echo $usuario["edad"]; ?> años</p>
                <p><strong>Objetivo:</strong> <?php echo $usuario["objetivo"]; ?></p>
                <p><strong>Nivel:</strong> <?php echo $usuario["nivel"]; ?></p>
                <p><strong>Estado:</strong> Usuario activo</p>
            </div>

            <div class="panel-card">
                <h3>Resumen</h3>
                <p>
                    Este módulo permite visualizar la información principal del usuario,
                    facilitando una mejor organización dentro de la plataforma PowerNess.
                </p>
            </div>
        </section>
    </main>

</div>
</body>
</html>