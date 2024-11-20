<?php
// Iniciamos la sesión para manejar variables de sesión entre páginas
session_start();

// Verificamos si existen las variables de sesión necesarias (usuario y dni)
// Si no existen, el usuario no está autenticado y se redirige al inicio
if (!isset($_SESSION['usuario']) && !isset($_SESSION['dni'])) {
    header("Location: ../index.php");
    exit;
}

// Verificamos si el rol del usuario es 'encargado'
// Esta es una medida de seguridad para evitar que otros roles accedan a esta sección
if ($_SESSION['rol'] != 'encargado') {
    header("Location: ../index.php");
    exit;
}
?>