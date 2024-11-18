<?php
include 'sesion_encargado.php';
include '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "DELETE FROM productos WHERE id=$id";

if (mysqli_query($conexion, $sql)) {
    header("Location: listar_productos.php?mensaje=Producto eliminado exitosamente");
} else {
    echo "Error: " . mysqli_error($conexion);
}

$conexion->close();
?>