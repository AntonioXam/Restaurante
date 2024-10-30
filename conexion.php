<?php
// conexion.php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$basededatos = "restaurante";

$conexion = mysqli_connect($servidor ,$usuario,$clave,$basededatos);
//comprobar la errror de conexion

mysqli_error($conexion);

// caracteres especiales

mysqli_set_charset($conexion, "utf8");


//compobar error de conexion

mysqli_error($conexion);

