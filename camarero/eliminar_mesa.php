<?php
include '../sesion.php';
include '../conexion.php';

// Obtener el ID de la mesa
$id = $_GET['id'];

// Eliminar mesa
$query = "DELETE FROM mesas WHERE id = $id";
mysqli_query($conexion, $query);

// Redirigir a la página de mesas
header("Location: mesas.php");
