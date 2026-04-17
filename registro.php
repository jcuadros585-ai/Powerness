<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $password = $_POST["password"];
    $edad = $_POST["edad"];
    $objetivo = $_POST["objetivo"];
    $nivel = $_POST["nivel"];

    $sql = "INSERT INTO usuarios (nombre, correo, password, edad, objetivo, nivel) 
            VALUES ('$nombre', '$correo', '$password', '$edad', '$objetivo', '$nivel')";

    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Usuario registrado correctamente'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - PowerNess</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="form-page">
    <div class="form-card">
        <h2>Crear cuenta</h2>
        <p class="form-subtitle">Completa tus datos para acceder a PowerNess.</p>

        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="number" name="edad" placeholder="Edad" required>

            <input type="text" name="objetivo" placeholder="Objetivo físico (ej. Ganancia muscular)" required>

            <select name="nivel" required>
                <option value="">Selecciona tu nivel</option>
                <option value="Principiante">Principiante</option>
                <option value="Intermedio">Intermedio</option>
                <option value="Avanzado">Avanzado</option>
            </select>

            <button type="submit">Registrarse</button>
        </form>

        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</div>

</body>
</html>