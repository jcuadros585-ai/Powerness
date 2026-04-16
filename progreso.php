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
    <title>Progreso - PowerNess</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="contenedor">
    <h2>Registrar progreso</h2>

    <form method="POST">
        <input type="number" step="0.01" name="peso" placeholder="Peso en kg" required><br><br>
        <input type="number" step="0.01" name="estatura" placeholder="Estatura en metros" required><br><br>
        <input type="date" name="fecha" required><br><br>
        <button type="submit" class="btn">Guardar progreso</button>
    </form>

    <br><br>
    <h2>Historial de progreso</h2>

    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; background:white; color:black;">
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
            <td><?php echo $fila["peso"]; ?></td>
            <td><?php echo $fila["estatura"]; ?></td>
            <td><?php echo $fila["fecha"]; ?></td>
        </tr>
        <?php } ?>
    </table>

    <br>
    <a href="dashboard.php" class="btn">Volver</a>
</div>

</body>
</html>