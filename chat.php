<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    echo "Acceso denegado";
    exit();
}

$usuarioId = isset($_GET["usuario_id"]) ? (int)$_GET["usuario_id"] : 0;
$mensajeSistema = "";

/* Responder mensaje */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioId = (int)$_POST["usuario_id"];
    $mensaje = trim($_POST["mensaje"]);

    if ($mensaje != "") {
        $mensajeSeguro = $conexion->real_escape_string($mensaje);
        $sqlInsert = "INSERT INTO chat_mensajes (usuario_id, remitente, mensaje) VALUES ($usuarioId, 'admin', '$mensajeSeguro')";
        if ($conexion->query($sqlInsert)) {
            $titulo = "Nueva respuesta del administrador";
            $texto = "Tienes una nueva respuesta en tu chat.";
            $sqlNoti = "INSERT INTO notificaciones (usuario_id, titulo, mensaje) VALUES ($usuarioId, '$titulo', '$texto')";
            $conexion->query($sqlNoti);

            header("Location: chat.php?usuario_id=$usuarioId");
            exit();
        }
    }
}

/* Lista de usuarios con chat */
$sqlUsuarios = "SELECT DISTINCT u.id, u.nombre, u.correo
                FROM usuarios u
                INNER JOIN chat_mensajes c ON u.id = c.usuario_id
                ORDER BY u.nombre ASC";
$usuarios = $conexion->query($sqlUsuarios);

/* Mensajes del usuario seleccionado */
$mensajes = null;
if ($usuarioId > 0) {
    $sqlMensajes = "SELECT * FROM chat_mensajes WHERE usuario_id = $usuarioId ORDER BY fecha ASC";
    $mensajes = $conexion->query($sqlMensajes);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Admin - PowerNess</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="dashboard-page">
    <aside class="sidebar">
        <div class="brand-box">
            <h2>PowerNess</h2>
            <p>Panel administrador</p>
        </div>

        <nav class="sidebar-nav">
            <a href="dashboard.php">Inicio</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="dietas.php">Dietas</a>
            <a href="registros.php">Registros</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="chat.php" class="activo">Chat</a>
            <a href="../dashboard.php">Volver al panel</a>
            <a href="../logout.php">Cerrar sesion</a>
        </nav>
    </aside>

    <main class="dashboard-main">
        <div class="topbar">
            <div class="topbar-left">
                <h1>Chat administrador</h1>
                <p>Visualiza y responde las preguntas enviadas por los usuarios.</p>
            </div>
            <div class="status-chip">Soporte activo</div>
        </div>

        <section class="panel-card">
            <h2>Seleccionar conversacion</h2>
            <div class="table-wrap">
                <table>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Accion</th>
                    </tr>
                    <?php if ($usuarios && $usuarios->num_rows > 0) { ?>
                        <?php while ($fila = $usuarios->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $fila["nombre"]; ?></td>
                                <td><?php echo $fila["correo"]; ?></td>
                                <td><a class="btn" href="chat.php?usuario_id=<?php echo $fila["id"]; ?>">Ver chat</a></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3">No hay conversaciones registradas.</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </section>

        <?php if ($usuarioId > 0) { ?>
        <section class="panel-card" style="margin-top: 24px;">
            <h2>Conversacion</h2>
            <div class="chat-box">
                <?php if ($mensajes && $mensajes->num_rows > 0) { ?>
                    <?php while ($fila = $mensajes->fetch_assoc()) { ?>
                        <div class="chat-message <?php echo $fila["remitente"] == "usuario" ? "msg-user" : "msg-admin"; ?>">
                            <strong><?php echo $fila["remitente"] == "usuario" ? "Usuario" : "Administrador"; ?>:</strong>
                            <p><?php echo nl2br(htmlspecialchars($fila["mensaje"])); ?></p>
                            <small><?php echo $fila["fecha"]; ?></small>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No hay mensajes para este usuario.</p>
                <?php } ?>
            </div>
        </section>

        <section class="panel-card" style="margin-top: 24px;">
            <h2>Responder</h2>
            <form method="POST">
                <input type="hidden" name="usuario_id" value="<?php echo $usuarioId; ?>">
                <textarea name="mensaje" rows="5" placeholder="Escribe una respuesta..."></textarea>
                <div style="margin-top: 16px;">
                    <button type="submit" class="btn">Enviar respuesta</button>
                </div>
            </form>
        </section>
        <?php } ?>
    </main>
</div>

</body>
</html>