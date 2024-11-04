<?php
include '../sesion.php';
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mesa_id = $_POST['mesa_id'];

    $query = "UPDATE pedidos SET estado = 'enviado' WHERE mesa_id = $mesa_id AND estado = 'pendiente'";
    mysqli_query($conexion, $query);

    header("Location: gestionar_pedido.php?mesa_id=$mesa_id");
    exit();
}
?>
