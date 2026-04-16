<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rutinas - PowerNess</title>
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
            <a href="rutinas.php" class="activo">Rutinas</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">
        <div class="topbar">
            <div class="topbar-left">
                <h1>Rutinas de entrenamiento</h1>
                <p>Escoge una rutina según tu objetivo físico.</p>
            </div>
            <div class="status-chip">Módulo activo</div>
        </div>

        <section class="resumen-grid">
            <div class="resumen-card">
                <h3>Hipertrofia</h3>
                <p>Ideal para aumento de masa muscular.</p>
                <ul>
                    <li>Pecho + tríceps</li>
                    <li>Espalda + bíceps</li>
                    <li>Pierna completa</li>
                </ul>
            </div>

            <div class="resumen-card">
                <h3>Definición</h3>
                <p>Ideal para perder grasa y mantener masa muscular.</p>
                <ul>
                    <li>Full body</li>
                    <li>HIIT</li>
                    <li>Cardio + core</li>
                </ul>
            </div>

            <div class="resumen-card">
                <h3>Fuerza</h3>
                <p>Ideal para mejorar rendimiento y levantamientos.</p>
                <ul>
                    <li>5x5</li>
                    <li>Power básico</li>
                    <li>Compuestos pesados</li>
                </ul>
            </div>
        </section>
    </main>

</div>

</body>
</html>