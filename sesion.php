<?php
// Iniciar o reanudar la sesión existente
session_start();

// Verificar si el usuario está autenticado
// Comprueba la existencia de las variables de sesión 'usuario' y 'dni'
// Si no existen, redirige al usuario a la página de inicio
if (!isset($_SESSION['usuario']) && !isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit;
}

// ...existing code...
?>


