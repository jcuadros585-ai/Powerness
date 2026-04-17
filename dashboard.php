<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];

/* Último peso registrado */
$sqlPeso = "SELECT peso, fecha 
            FROM progreso 
            WHERE usuario='$usuario' 
            ORDER BY fecha DESC 
            LIMIT 1";
$resPeso = $conexion->query($sqlPeso);

$pesoActual = "Sin datos";
$fechaUltimo = "Sin registros";

if ($resPeso && $resPeso->num_rows > 0) {
    $filaPeso = $resPeso->fetch_assoc();
    $pesoActual = $filaPeso["peso"] . " kg";
    $fechaUltimo = $filaPeso["fecha"];
}

/* Total de registros */
$sqlTotal = "SELECT COUNT(*) AS total 
             FROM progreso 
             WHERE usuario='$usuario'";
$resTotal = $conexion->query($sqlTotal);
$totalRegistros = 0;

if ($resTotal && $resTotal->num_rows > 0) {
    $filaTotal = $resTotal->fetch_assoc();
    $totalRegistros = $filaTotal["total"];
}

/* Rutinas disponibles */
$totalRutinas = 3;

/* Datos para gráfico */
$sqlGrafico = "SELECT fecha, peso 
               FROM progreso 
               WHERE usuario='$usuario' 
               ORDER BY fecha ASC";
$resGrafico = $conexion->query($sqlGrafico);

$fechas = [];
$pesos = [];

if ($resGrafico && $resGrafico->num_rows > 0) {
    while ($filaGrafico = $resGrafico->fetch_assoc()) {
        $fechas[] = $filaGrafico["fecha"];
        $pesos[] = (float)$filaGrafico["peso"];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel principal - PowerNess</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="dieta.php">Dieta</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Hola, <?php echo $usuario; ?></h1>
                <p>Bienvenido a tu panel principal de entrenamiento.</p>
            </div>
            <div class="status-chip">Sesión activa</div>
        </div>

        <section class="welcome-banner">
            <h2>Tu entrenamiento empieza con constancia</h2>
            <p>
                Desde este panel puedes revisar tu progreso físico, acceder a tus rutinas,
                consultar tu plan de alimentación y visualizar tu información personal
                dentro de PowerNess.
            </p>
        </section>

        <section class="metric-grid">
            <div class="metric-card">
                <span>Último peso registrado</span>
                <h3><?php echo $pesoActual; ?></h3>
                <p>Fecha del último registro: <?php echo $fechaUltimo; ?></p>
            </div>

            <div class="metric-card">
                <span>Total de registros</span>
                <h3><?php echo $totalRegistros; ?></h3>
                <p>Cantidad de controles físicos almacenados en el sistema.</p>
            </div>

            <div class="metric-card">
                <span>Rutinas disponibles</span>
                <h3><?php echo $totalRutinas; ?></h3>
                <p>Programas organizados en hipertrofia, definición y fuerza.</p>
            </div>
        </section>

        <section class="content-grid">
            <div class="panel-card">
                <h2>Acciones principales</h2>
                <p>
                    Accede rápidamente a las secciones más importantes para continuar
                    con tu seguimiento dentro de la plataforma.
                </p>

                <div class="quick-actions">
                    <a href="progreso.php" class="btn">Registrar progreso</a>
                    <a href="rutinas.php" class="btn">Ver rutinas</a>
                    <a href="dieta.php" class="btn">Ver dieta</a>
                    <a href="perfil.php" class="btn">Mi perfil</a>
                </div>
            </div>

            <div class="panel-card">
                <h3>Resumen general</h3>
                <p>
                    PowerNess centraliza el seguimiento del usuario, permitiendo registrar
                    datos físicos, consultar rutinas de entrenamiento y mantener una
                    organización básica del plan alimenticio.
                </p>
            </div>
        </section>

        <section class="panel-card" style="margin-top: 24px;">
            <h2>Evolución del peso</h2>
            <p>
                El siguiente gráfico muestra la variación del peso registrada por el usuario
                a lo largo del tiempo.
            </p>

            <?php if (count($fechas) > 0) { ?>
                <canvas id="graficoProgreso" height="100"></canvas>
            <?php } else { ?>
                <p style="margin-top: 18px;">Aún no hay registros suficientes para mostrar el gráfico.</p>
            <?php } ?>
        </section>

    </main>
</div>

<?php if (count($fechas) > 0) { ?>
<script>
const ctx = document.getElementById('graficoProgreso');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($fechas); ?>,
        datasets: [{
            label: 'Peso (kg)',
            data: <?php echo json_encode($pesos); ?>,
            borderColor: '#ff2a2a',
            backgroundColor: 'rgba(255, 42, 42, 0.15)',
            borderWidth: 3,
            tension: 0.3,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#ff2a2a'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    color: '#ffffff'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#cfcfcf'
                },
                grid: {
                    color: 'rgba(255,255,255,0.08)'
                }
            },
            y: {
                ticks: {
                    color: '#cfcfcf'
                },
                grid: {
                    color: 'rgba(255,255,255,0.08)'
                }
            }
        }
    }
});
</script>
<?php } ?>

</body>
</html>