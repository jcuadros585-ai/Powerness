<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE correo='$correo' AND password='$password'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();

    $_SESSION["usuario"] = $fila["nombre"];
    $_SESSION["rol"] = $fila["rol"]; // login de mi adminnnnnnnnnnnnnnnnnnnnnnnn

    header("Location: dashboard.php");
    exit();
}
    } else {
        echo "<script>alert('Correo o contraseña incorrectos');</script>";
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - PowerNess</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="form-page">
    <div class="form-card">
        <h2>Iniciar sesión</h2>
        <p class="form-subtitle">Accede a tu panel de entrenamiento y seguimiento físico.</p>

        <form method="POST">
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>

        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>
</div>

</body>
</html>