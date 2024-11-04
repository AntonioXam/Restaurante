<?php
// conexion.php
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_datos = 'restaurante';

$conexion = mysqli_connect($host, $usuario, $contrasena, $base_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

//caracteres especiales
mysqli_set_charset($conexion, 'utf8');

//comprobar errores en la conexion

mysqli_error($conexion);

