<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_SESSION["usuario"];
    $peso = $_POST["peso"];
    $estatura = $_POST["estatura"];
    $fecha = $_POST["fecha"];

    $sql = "INSERT INTO progreso (usuario, peso, estatura, fecha)
            VALUES ('$usuario', '$peso', '$estatura', '$fecha')";

    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Progreso registrado correctamente');</script>";
    } else {
        echo "Error: " . $conexion->error;
    }
}

$usuario = $_SESSION["usuario"];
$consulta = "SELECT * FROM progreso WHERE usuario='$usuario' ORDER BY fecha DESC";
$resultado = $conexion->query($consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi progreso - PowerNess</title>
    <link rel="stylesheet" href="css/progreso.css">
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
            <a href="progreso.php" class="activo">Mi progreso</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">
        <div class="topbar">
            <div class="topbar-left">
                <h1>Mi progreso</h1>
                <p>Registra tus datos y consulta tu historial físico.</p>
            </div>
            <div class="status-chip">Seguimiento activo</div>
        </div>

        <section class="content-grid">
            <div class="panel-card">
                <h2>Registrar progreso</h2>
                <form method="POST">
                    <input type="number" step="0.01" name="peso" placeholder="Peso en kg" required>
                    <input type="number" step="0.01" name="estatura" placeholder="Estatura en metros" required>
                    <input type="date" name="fecha" required>
                    <button type="submit">Guardar progreso</button>
                </form>
            </div>

            <div class="panel-card">
                <h3>Descripción</h3>
                <p>
                    En este apartado el usuario puede registrar información básica de su evolución,
                    como peso, estatura y fecha, permitiendo llevar un mejor control de su avance.
                </p>
            </div>
        </section>

        <section class="panel-info">
            <h2>Historial registrado</h2>

            <div class="table-wrap">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Peso</th>
                        <th>Estatura</th>
                        <th>Fecha</th>
                    </tr>

                    <?php while ($fila = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $fila["id"]; ?></td>
                        <td><?php echo $fila["usuario"]; ?></td>
                        <td><?php echo $fila["peso"]; ?> kg</td>
                        <td><?php echo $fila["estatura"]; ?> m</td>
                        <td><?php echo $fila["fecha"]; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </section>
    </main>

</div>

</body>
</html>