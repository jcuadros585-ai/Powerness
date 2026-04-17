<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$dieta = null;
$datosUsuario = null;

/* Buscar datos del usuario */
$sqlUsuario = "SELECT * FROM usuarios WHERE nombre='$usuario' LIMIT 1";
$resUsuario = $conexion->query($sqlUsuario);

if ($resUsuario && $resUsuario->num_rows > 0) {
    $datosUsuario = $resUsuario->fetch_assoc();
    $usuarioId = (int) $datosUsuario["id"];

    $sqlDieta = "SELECT * FROM dietas WHERE usuario_id = $usuarioId LIMIT 1";
    $resDieta = $conexion->query($sqlDieta);

    if ($resDieta && $resDieta->num_rows > 0) {
        $dieta = $resDieta->fetch_assoc();
    }
}

/* Buscar último peso */
$sqlPeso = "SELECT peso, fecha FROM progreso WHERE usuario='$usuario' ORDER BY fecha DESC, id DESC LIMIT 1";
$resPeso = $conexion->query($sqlPeso);

$pesoActual = "Sin registros";
$fechaPeso = "-";

if ($resPeso && $resPeso->num_rows > 0) {
    $filaPeso = $resPeso->fetch_assoc();
    $pesoActual = $filaPeso["peso"];
    $fechaPeso = $filaPeso["fecha"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin") { ?>
                <a href="admin/dashboard.php">Panel Admin</a>
            <?php } ?>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Mi plan de alimentación</h1>
                <p>Consulta tu dieta personalizada y los ajustes realizados según tu progreso.</p>
            </div>
            <div class="status-chip">Plan activo</div>
        </div>

        <section class="welcome-banner">
            <h2>Alimentación enfocada en tu objetivo</h2>
            <p>
                En este módulo puedes visualizar tu planificación alimenticia actual,
                organizada según tus necesidades y el seguimiento de tu evolución física.
            </p>
        </section>

        <section class="metric-grid">
            <div class="metric-card">
                <span>Peso actual</span>
                <h3><?php echo is_numeric($pesoActual) ? $pesoActual . " kg" : $pesoActual; ?></h3>
                <p>Último registro: <?php echo $fechaPeso; ?></p>
            </div>

            <div class="metric-card">
                <span>Objetivo</span>
                <h3><?php echo $datosUsuario ? $datosUsuario["objetivo"] : "No disponible"; ?></h3>
                <p>Nivel: <?php echo $datosUsuario ? $datosUsuario["nivel"] : "No disponible"; ?></p>
            </div>

            <div class="metric-card">
                <span>Estado del plan</span>
                <h3><?php echo $dieta ? "Actualizado" : "Pendiente"; ?></h3>
                <p>Tu dieta se ajusta de acuerdo con tus registros y objetivo físico.</p>
            </div>
        </section>

        <?php if ($dieta) { ?>

        <section class="content-grid">
            <div class="panel-card">
                <h2>Ajustes nutricionales</h2>
                <p>Estos valores indican los aumentos específicos definidos dentro de tu plan actual.</p>

                <div class="resumen-grid">
                    <div class="resumen-card">
                        <h3>Carbohidratos</h3>
                        <p>+<?php echo $dieta["carbo_extra"]; ?> g</p>
                    </div>

                    <div class="resumen-card">
                        <h3>Proteínas</h3>
                        <p>+<?php echo $dieta["prote_extra"]; ?> g</p>
                    </div>

                    <div class="resumen-card">
                        <h3>Grasas</h3>
                        <p>+<?php echo $dieta["grasa_extra"]; ?> g</p>
                    </div>
                </div>
            </div>

            <div class="panel-card">
                <h3>Resumen del plan</h3>
                <p>
                    Este plan alimenticio ha sido organizado considerando tu objetivo actual,
                    tu peso más reciente registrado en el sistema y los ajustes necesarios para
                    mejorar tu rendimiento.
                </p>
            </div>
        </section>

        <section class="resumen-grid" style="margin-top: 24px;">
            <div class="resumen-card">
                <h3>Desayuno</h3>
                <p><?php echo nl2br($dieta["desayuno"]); ?></p>
            </div>

            <div class="resumen-card">
                <h3>Almuerzo</h3>
                <p><?php echo nl2br($dieta["almuerzo"]); ?></p>
            </div>

            <div class="resumen-card">
                <h3>Merienda</h3>
                <p><?php echo nl2br($dieta["merienda"]); ?></p>
            </div>

            <div class="resumen-card">
                <h3>Cena</h3>
                <p><?php echo nl2br($dieta["cena"]); ?></p>
            </div>
        </section>

        <section class="panel-card" style="margin-top: 24px;">
            <h2>Observaciones del plan</h2>
            <p><?php echo nl2br($dieta["observaciones"]); ?></p>
        </section>

        <?php } else { ?>

        <section class="panel-card">
            <h2>Sin dieta registrada</h2>
            <p>
                Aún no tienes un plan alimenticio asignado en el sistema. Cuando el administrador
                registre tu dieta personalizada, podrás visualizarla aquí con todos sus ajustes.
            </p>
        </section>

        <?php } ?>

    </main>
</div>

</body>
</html>