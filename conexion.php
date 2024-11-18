<?php
// conexion.php
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_datos = 'restaurante';

// Establecer conexión con la base de datos
$conexion = mysqli_connect($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configurar caracteres especiales
mysqli_set_charset($conexion, 'utf8mb4');

// Comprobar errores en la conexión
mysqli_error($conexion);
?>

