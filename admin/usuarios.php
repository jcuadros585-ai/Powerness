<?php
session_start();
include("../conexion.php");

if (!isset($_SESSION["usuario"])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin") {
    echo "Acceso denegado";
    exit();
}

$adminActual = $_SESSION["usuario"];

/* Eliminar usuario */
if (isset($_GET["eliminar"])) {
    $idEliminar = (int) $_GET["eliminar"];

    $sqlBuscar = "SELECT * FROM usuarios WHERE id = $idEliminar";
    $resBuscar = $conexion->query($sqlBuscar);

    if ($resBuscar && $resBuscar->num_rows > 0) {
        $usuarioEliminar = $resBuscar->fetch_assoc();

        if ($usuarioEliminar["nombre"] != $adminActual) {
            $sqlEliminar = "DELETE FROM usuarios WHERE id = $idEliminar";
            $conexion->query($sqlEliminar);
            header("Location: usuarios.php");
            exit();
        }
    }
}

/* Cambiar rol */
if (isset($_GET["rol"]) && isset($_GET["id"])) {
    $nuevoRol = $_GET["rol"];
    $idRol = (int) $_GET["id"];

    if ($nuevoRol == "admin" || $nuevoRol == "usuario") {
        $sqlRol = "UPDATE usuarios SET rol='$nuevoRol' WHERE id=$idRol";
        $conexion->query($sqlRol);
        header("Location: usuarios.php");
        exit();
    }
}

/* Obtener usuarios */
$sqlUsuarios = "SELECT * FROM usuarios ORDER BY id DESC";
$usuarios = $conexion->query($sqlUsuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Panel Admin</title>
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
            <a href="usuarios.php" class="activo">Usuarios</a>
            <a href="dietas.php">Dietas</a>
            <a href="registros.php">Registros</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="../dashboard.php">Volver al panel</a>
            <a href="../logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Gestión de usuarios</h1>
                <p>Consulta, administra y actualiza la información de los usuarios registrados.</p>
            </div>
            <div class="status-chip">Módulo activo</div>
        </div>

        <section class="welcome-banner">
            <h2>Administración de cuentas</h2>
            <p>
                En este módulo se muestra la lista de usuarios registrados en la plataforma,
                permitiendo supervisar sus datos principales, modificar su rol y eliminar cuentas
                cuando sea necesario.
            </p>
        </section>

        <section class="panel-card">
            <h2>Lista de usuarios registrados</h2>

            <div class="table-wrap">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Edad</th>
                        <th>Objetivo</th>
                        <th>Nivel</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>

                    <?php if ($usuarios && $usuarios->num_rows > 0) { ?>
                        <?php while ($fila = $usuarios->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $fila["id"]; ?></td>
                                <td><?php echo $fila["nombre"]; ?></td>
                                <td><?php echo $fila["correo"]; ?></td>
                                <td><?php echo $fila["edad"]; ?></td>
                                <td><?php echo $fila["objetivo"]; ?></td>
                                <td><?php echo $fila["nivel"]; ?></td>
                                <td><?php echo $fila["rol"]; ?></td>
                                <td>
                                    <?php if ($fila["rol"] == "usuario") { ?>
                                        <a href="usuarios.php?id=<?php echo $fila["id"]; ?>&rol=admin" class="btn btn-small">Hacer admin</a>
                                    <?php } else { ?>
                                        <a href="usuarios.php?id=<?php echo $fila["id"]; ?>&rol=usuario" class="btn btn-small">Quitar admin</a>
                                    <?php } ?>

                                    <?php if ($fila["nombre"] != $adminActual) { ?>
                                        <a href="usuarios.php?eliminar=<?php echo $fila["id"]; ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
                                    <?php } else { ?>
                                        <span class="badge-admin">Tu cuenta</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8">No hay usuarios registrados.</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </section>

    </main>
</div>

</body>
</html>