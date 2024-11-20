<?php

// Incluir archivos de sesión y conexión
include 'sesion_encargado.php';
include '../conexion.php';

// Obtener ID del producto
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Elimina el producto especificado
// Ejemplo: DELETE FROM productos WHERE id = 5
$sql = "DELETE FROM productos WHERE id=$id";

// Ejecutar la consulta y redirigir con mensaje
if (mysqli_query($conexion, $sql)) {
    header("Location: listar_productos.php?mensaje=Producto eliminado exitosamente");
} else {
    echo "Error: " . mysqli_error($conexion);
}

// Cerrar conexión
$conexion->close();
?>