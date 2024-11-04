<?php
include '../sesion.php';
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];

    $query = "UPDATE mesas SET estado = 'activa' WHERE id = $mesa_id";
    mysqli_query($conexion, $query);

    $query = "INSERT INTO pedidos (mesa_id, estado, total) VALUES ($mesa_id, 'pendiente', 0.00)";
    mysqli_query($conexion, $query);

    header("Location: gestionar_pedido.php?mesa_id=$mesa_id");
    exit();
}
?>
