<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario']) && !isset($_SESSION['dni'])) {
    header("Location: ../index.php");
    exit;
}

// Verificar si el usuario es un encargado
if ($_SESSION['rol'] != 'encargado') {
    header("Location: ../index.php");
    exit;
}


?>