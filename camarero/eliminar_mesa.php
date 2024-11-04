<?php
session_start();
include '../sesion.php';
include '../conexion.php';

$camarero_id = $_SESSION['usuario_id'];

// Obtener el ID de la mesa
$id = $_GET['id'];

// Verificar que la mesa pertenece al camarero actual
$query_verificar = "SELECT * FROM mesas WHERE id = $id AND camarero_id = $camarero_id";
$result_verificar = mysqli_query($conexion, $query_verificar);
if (mysqli_num_rows($result_verificar) == 0) {
    die("No tienes permiso para eliminar esta mesa.");
}

// Eliminar mesa
$query = "DELETE FROM mesas WHERE id = $id AND camarero_id = $camarero_id";
mysqli_query($conexion, $query);

// Redirigir a la página de mesas
header("Location: mesas.php");
?>
