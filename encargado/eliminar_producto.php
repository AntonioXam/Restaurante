<?php
include '../sesion.php';
include '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: listar_productos.php");
    exit;
}

$id = $_GET['id'];

// Verificar si el producto existe
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    echo "Producto no encontrado.";
    exit;
}

// Eliminar el producto
$sql = "DELETE FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: listar_productos.php");
} else {
    echo "Error al eliminar el producto: " . $conexion->error;
}

$stmt->close();
$conexion->close();
?>