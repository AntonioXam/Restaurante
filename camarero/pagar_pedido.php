<?php

include '../sesion.php';
include '../conexion.php';

$pedido_id = $_GET['pedido_id'];
$mesa_id = $_GET['mesa_id'];

// Actualizar estado del pedido a 'pagado'
$query_pagar_pedido = "UPDATE pedidos SET estado = 'pagado' WHERE id = $pedido_id";
mysqli_query($conexion, $query_pagar_pedido);

// Redirigir a la página de pedidos
header("Location: pedidos.php?mesa_id=$mesa_id");
exit();
?>
