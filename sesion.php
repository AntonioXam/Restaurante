<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario']) && !isset($_SESSION['dni'])) {
    header("Location: index.php");
    exit;
}


