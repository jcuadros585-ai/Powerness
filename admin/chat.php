<?php
session_start();
include("../conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION["rol"] != "admin") {
    echo "Acceso denegado";
    exit();
}

/* Obtener usuarios con mensajes */
$sqlUsuarios = "SELECT DISTINCT u.id, u.nombre, u.correo
FROM usuarios u
INNER JOIN chat_mensajes c ON u.id = c.usuario_id";

$usuarios = $conexion->query($sqlUsuarios);

$usuarioId = isset($_GET["usuario_id"]) ? (int)$_GET["usuario_id"] : 0;

/* Enviar respuesta */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioId = (int)$_POST["usuario_id"];
    $mensaje = $conexion->real_escape_string($_POST["mensaje"]);

    $conexion->query("INSERT INTO chat_mensajes (usuario_id, remitente, mensaje)
    VALUES ($usuarioId, 'admin', '$mensaje')");

    header("Location: chat.php?usuario_id=$usuarioId");
    exit();
}

/* Obtener mensajes */
$mensajes = null;
if ($usuarioId > 0) {
    $mensajes = $conexion->query("SELECT * FROM chat_mensajes WHERE usuario_id = $usuarioId ORDER BY fecha ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<h1>Chat Administrador</h1>

<h2>Usuarios</h2>
<?php while($u = $usuarios->fetch_assoc()) { ?>
    <p>
        <?php echo $u["nombre"]; ?> -
        <a href="chat.php?usuario_id=<?php echo $u["id"]; ?>">Ver chat</a>
    </p>
<?php } ?>

<?php if ($usuarioId > 0) { ?>
    <h2>Conversación</h2>

    <?php while($m = $mensajes->fetch_assoc()) { ?>
        <p>
            <strong><?php echo $m["remitente"]; ?>:</strong>
            <?php echo $m["mensaje"]; ?>
        </p>
    <?php } ?>

    <form method="POST">
        <input type="hidden" name="usuario_id" value="<?php echo $usuarioId; ?>">
        <textarea name="mensaje"></textarea>
        <button type="submit">Responder</button>
    </form>
<?php } ?>

</body>
</html>