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
            <a href="rutinas.php" class="activo">Rutinas</a>
            <a href="dieta.php">Dieta</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">
        <div class="topbar">
            <div class="topbar-left">
                <h1>Rutinas de entrenamiento</h1>
                <p>Programas organizados según el objetivo físico del usuario.</p>
            </div>
            <div class="status-chip">Rutinas activas</div>
        </div>

        <section class="resumen-grid">
            <div class="resumen-card">
                <h3>Hipertrofia</h3>
                <p>Rutina orientada al aumento de masa muscular.</p>
                <ul>
                    <li>Pecho y tríceps</li>
                    <li>Espalda y bíceps</li>
                    <li>Piernas completas</li>
                </ul>
            </div>

            <div class="resumen-card">
                <h3>Definición</h3>
                <p>Rutina enfocada en reducir grasa corporal y mejorar condición física.</p>
                <ul>
                    <li>Full body</li>
                    <li>HIIT</li>
                    <li>Cardio y abdomen</li>
                </ul>
            </div>

            <div class="resumen-card">
                <h3>Fuerza</h3>
                <p>Rutina diseñada para mejorar rendimiento y levantamientos básicos.</p>
                <ul>
                    <li>Sentadilla</li>
                    <li>Press banca</li>
                    <li>Peso muerto</li>
                </ul>
            </div>
        </section>
    </main>

</div>

</body>
</html>