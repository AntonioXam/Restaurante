<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario']) && !isset($_SESSION['dni'])) {
    // Redirigir al índice si no está logueado
    header("Location: ../index.php");
    exit;
}

// Verificar si el usuario es encargado
if ($_SESSION['rol'] !== 'encargado') {
    // Redirigir al índice si no es encargado
    header("Location: ../index.php");
    exit;
}

// ...existing code...
?>