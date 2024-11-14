<?php
$servername = "localhost";
$username = "root"; // Cambia 'tu_usuario' por tu usuario de MySQL real
$password = ""; // Cambia 'tu_contraseña' por tu contraseña de MySQL real
$dbname = "restaurante";

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

// Establecer conjunto de caracteres
$conexion->set_charset("utf8mb4");
?>

