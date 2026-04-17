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
?>