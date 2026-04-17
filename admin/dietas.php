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

$mensaje = "";
$usuarioIdSeleccionado = null;
$datosUsuario = null;
$dietaActual = null;
$pesoActual = "Sin registros";

/* Capturar usuario seleccionado */
if (isset($_GET["usuario_id"])) {
    $usuarioIdSeleccionado = (int) $_GET["usuario_id"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = (int) $_POST["usuario_id"];
    $peso_actual = (float) $_POST["peso_actual"];
    $objetivo = $conexion->real_escape_string($_POST["objetivo"]);
    $carbo_extra = (int) $_POST["carbo_extra"];
    $prote_extra = (int) $_POST["prote_extra"];
    $grasa_extra = (int) $_POST["grasa_extra"];
    $desayuno = $conexion->real_escape_string($_POST["desayuno"]);
    $almuerzo = $conexion->real_escape_string($_POST["almuerzo"]);
    $merienda = $conexion->real_escape_string($_POST["merienda"]);
    $cena = $conexion->real_escape_string($_POST["cena"]);
    $observaciones = $conexion->real_escape_string($_POST["observaciones"]);

    $sqlExiste = "SELECT id FROM dietas WHERE usuario_id = $usuario_id LIMIT 1";
    $resExiste = $conexion->query($sqlExiste);

    if ($resExiste && $resExiste->num_rows > 0) {
        $sqlUpdate = "UPDATE dietas SET
                        peso_actual = $peso_actual,
                        objetivo = '$objetivo',
                        carbo_extra = $carbo_extra,
                        prote_extra = $prote_extra,
                        grasa_extra = $grasa_extra,
                        desayuno = '$desayuno',
                        almuerzo = '$almuerzo',
                        merienda = '$merienda',
                        cena = '$cena',
                        observaciones = '$observaciones'
                      WHERE usuario_id = $usuario_id";
        if ($conexion->query($sqlUpdate)) {
            $mensaje = "Dieta actualizada correctamente.";
        } else {
            $mensaje = "Error al actualizar la dieta.";
        }
    } else {
        $sqlInsert = "INSERT INTO dietas
                        (usuario_id, peso_actual, objetivo, carbo_extra, prote_extra, grasa_extra, desayuno, almuerzo, merienda, cena, observaciones)
                      VALUES
                        ($usuario_id, $peso_actual, '$objetivo', $carbo_extra, $prote_extra, $grasa_extra, '$desayuno', '$almuerzo', '$merienda', '$cena', '$observaciones')";
        if ($conexion->query($sqlInsert)) {
            $mensaje = "Dieta registrada correctamente.";
        } else {
            $mensaje = "Error al registrar la dieta.";
        }
    }

    $usuarioIdSeleccionado = $usuario_id;
}

/* Cargar datos del usuario seleccionado */
if ($usuarioIdSeleccionado) {
    $sqlUsuario = "SELECT * FROM usuarios WHERE id = $usuarioIdSeleccionado LIMIT 1";
    $resUsuario = $conexion->query($sqlUsuario);

    if ($resUsuario && $resUsuario->num_rows > 0) {
        $datosUsuario = $resUsuario->fetch_assoc();

        $nombreUsuario = $conexion->real_escape_string($datosUsuario["nombre"]);

        $sqlPeso = "SELECT peso, fecha 
                    FROM progreso 
                    WHERE usuario = '$nombreUsuario' 
                    ORDER BY fecha DESC, id DESC 
                    LIMIT 1";
        $resPeso = $conexion->query($sqlPeso);

        if ($resPeso && $resPeso->num_rows > 0) {
            $filaPeso = $resPeso->fetch_assoc();
            $pesoActual = $filaPeso["peso"];
        }

        $sqlDieta = "SELECT * FROM dietas WHERE usuario_id = $usuarioIdSeleccionado LIMIT 1";
        $resDieta = $conexion->query($sqlDieta);

        if ($resDieta && $resDieta->num_rows > 0) {
            $dietaActual = $resDieta->fetch_assoc();
        }
    }
}

/* Lista de usuarios */
$sqlUsuarios = "SELECT id, nombre FROM usuarios ORDER BY nombre ASC";
$usuarios = $conexion->query($sqlUsuarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietas - Panel Admin</title>
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
            <a href="dietas.php" class="activo">Dietas</a>
            <a href="registros.php">Registros</a>
            <a href="rutinas.php">Rutinas</a>
            <a href="../dashboard.php">Volver al panel</a>
            <a href="../logout.php">Cerrar sesión</a>
        </nav>
    </aside>

    <main class="dashboard-main">

        <div class="topbar">
            <div class="topbar-left">
                <h1>Gestión de dietas</h1>
                <p>Asigna y actualiza planes alimenticios personalizados para cada usuario.</p>
            </div>
            <div class="status-chip">Módulo activo</div>
        </div>

        <section class="welcome-banner">
            <h2>Planificación nutricional personalizada</h2>
            <p>
                Desde este módulo puedes revisar el peso actual del usuario, su objetivo físico
                y ajustar la dieta según su evolución, incluyendo aumentos específicos de carbohidratos,
                proteínas y grasas.
            </p>
        </section>

        <?php if ($mensaje != "") { ?>
            <section class="panel-card" style="margin-bottom: 20px;">
                <p style="color:#86f0bf; font-weight:bold;"><?php echo $mensaje; ?></p>
            </section>
        <?php } ?>

        <section class="panel-card">
            <h2>Seleccionar usuario</h2>

            <form method="GET" class="admin-form-grid">
                <div>
                    <label>Usuario</label>
                    <select name="usuario_id" required>
                        <option value="">Selecciona un usuario</option>
                        <?php if ($usuarios && $usuarios->num_rows > 0) { ?>
                            <?php while ($filaUsuario = $usuarios->fetch_assoc()) { ?>
                                <option value="<?php echo $filaUsuario["id"]; ?>" <?php echo ($usuarioIdSeleccionado == $filaUsuario["id"]) ? "selected" : ""; ?>>
                                    <?php echo $filaUsuario["nombre"]; ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-button-box">
                    <button type="submit" class="btn">Cargar usuario</button>
                </div>
            </form>
        </section>

        <?php if ($usuarioIdSeleccionado && $datosUsuario) { ?>
        <section class="metric-grid" style="margin-top: 24px;">
            <div class="metric-card">
                <span>Usuario</span>
                <h3><?php echo $datosUsuario["nombre"]; ?></h3>
                <p>Correo: <?php echo $datosUsuario["correo"]; ?></p>
            </div>

            <div class="metric-card">
                <span>Peso actual</span>
                <h3><?php echo is_numeric($pesoActual) ? $pesoActual . " kg" : $pesoActual; ?></h3>
                <p>Último peso registrado en el sistema.</p>
            </div>

            <div class="metric-card">
                <span>Objetivo</span>
                <h3><?php echo $datosUsuario["objetivo"]; ?></h3>
                <p>Nivel: <?php echo $datosUsuario["nivel"]; ?></p>
            </div>
        </section>

        <section class="panel-card" style="margin-top: 24px;">
            <h2><?php echo $dietaActual ? "Actualizar dieta" : "Registrar dieta"; ?></h2>

            <form method="POST" class="diet-form">
                <input type="hidden" name="usuario_id" value="<?php echo $datosUsuario["id"]; ?>">
                <input type="hidden" name="objetivo" value="<?php echo $datosUsuario["objetivo"]; ?>">
                <input type="hidden" name="peso_actual" value="<?php echo is_numeric($pesoActual) ? $pesoActual : 0; ?>">

                <div class="diet-top-grid">
                    <div class="macro-card">
                        <label>Aumentar carbohidratos (g)</label>
                        <input type="number" name="carbo_extra" min="0" value="<?php echo $dietaActual["carbo_extra"] ?? 0; ?>">
                    </div>

                    <div class="macro-card">
                        <label>Aumentar proteínas (g)</label>
                        <input type="number" name="prote_extra" min="0" value="<?php echo $dietaActual["prote_extra"] ?? 0; ?>">
                    </div>

                    <div class="macro-card">
                        <label>Aumentar grasas (g)</label>
                        <input type="number" name="grasa_extra" min="0" value="<?php echo $dietaActual["grasa_extra"] ?? 0; ?>">
                    </div>
                </div>

                <div class="diet-box-grid">
                    <div class="meal-box">
                        <label>Desayuno</label>
                        <textarea name="desayuno" rows="6" placeholder="Escribe aquí el desayuno..."><?php echo $dietaActual["desayuno"] ?? ""; ?></textarea>
                    </div>

                    <div class="meal-box">
                        <label>Almuerzo</label>
                        <textarea name="almuerzo" rows="6" placeholder="Escribe aquí el almuerzo..."><?php echo $dietaActual["almuerzo"] ?? ""; ?></textarea>
                    </div>

                    <div class="meal-box">
                        <label>Merienda</label>
                        <textarea name="merienda" rows="6" placeholder="Escribe aquí la merienda..."><?php echo $dietaActual["merienda"] ?? ""; ?></textarea>
                    </div>

                    <div class="meal-box">
                        <label>Cena</label>
                        <textarea name="cena" rows="6" placeholder="Escribe aquí la cena..."><?php echo $dietaActual["cena"] ?? ""; ?></textarea>
                    </div>
                </div>

                <div class="meal-box full-width-box">
                    <label>Observaciones y ajustes</label>
                    <textarea name="observaciones" rows="5" placeholder="Escribe observaciones, cambios o ajustes según el progreso del usuario..."><?php echo $dietaActual["observaciones"] ?? ""; ?></textarea>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn">Guardar dieta</button>
                </div>
            </form>
        </section>
        <?php } ?>

    </main>
</div>

</body>
</html>