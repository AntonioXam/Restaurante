<?php
// Configuración de la conexión a la base de datos MySQL
// Servidor local por defecto de XAMPP
$host = 'localhost';
// Usuario root por defecto
$usuario = 'root';
// Contraseña vacía por defecto en XAMPP
$contrasena = '';
// Nombre de la base de datos del restaurante
$base_datos = 'restaurante';

// Establecer conexión con MySQL usando mysqli
// Esta función retorna un objeto de conexión o false en caso de error
$conexion = mysqli_connect($host, $usuario, $contrasena, $base_datos);

// Verificar si la conexión fue exitosa
// En caso de error, termina la ejecución y muestra el mensaje de error
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configurar el conjunto de caracteres de la conexión a UTF-8
// Esto permite manejar caracteres especiales y acentos correctamente
mysqli_set_charset($conexion, 'utf8mb4');

// Habilitar la verificación de errores en las consultas
mysqli_error($conexion);
?>

