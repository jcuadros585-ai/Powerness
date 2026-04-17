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
    <title>Dieta - PowerNess</title>
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
            <a href="dieta.php" class="activo">Dieta</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Plan de alimentación</h1>
                <p>Distribución diaria de comidas orientadas al rendimiento físico.</p>
            </div>
            <div class="status-chip">Plan activo</div>
        </div>

        <section class="resumen-grid">
            <div class="resumen-card">
                <h3>Desayuno</h3>
                <p>Avena con plátano, 4 claras y 2 huevos completos.</p>
            </div>

            <div class="resumen-card">
                <h3>Almuerzo</h3>
                <p>150 g de pollo a la plancha, arroz integral y ensalada fresca.</p>
            </div>

            <div class="resumen-card">
                <h3>Merienda</h3>
                <p>Yogur griego o batido de proteína con una fruta.</p>
            </div>

            <div class="resumen-card">
                <h3>Cena</h3>
                <p>Atún o pechuga de pollo con camote y verduras al vapor.</p>
            </div>
        </section>

        <section class="content-grid">
            <div class="panel-card">
                <h2>Objetivo nutricional</h2>
                <p>
                    El plan de alimentación está orientado a mantener una distribución equilibrada
                    de proteínas, carbohidratos y grasas saludables, favoreciendo el rendimiento
                    durante el entrenamiento y la recuperación muscular.
                </p>
            </div>

            <div class="panel-card">
                <h3>Recomendaciones</h3>
                <p>
                    Mantener una adecuada hidratación, respetar los horarios de comida y acompañar
                    la alimentación con constancia en la rutina de entrenamiento.
                </p>
            </div>
        </section>

    </main>
</div>

</body>
</html>