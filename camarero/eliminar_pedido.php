<?php
include '../sesion.php';
include '../conexion.php';

// Obtener el ID del detalle del pedido
$id = $_GET['id'];

// Obtener el pedido_id antes de eliminar el detalle
$query_pedido_id = "SELECT pedido_id FROM detalle_pedidos WHERE id = $id";
$result_pedido_id = mysqli_query($conexion, $query_pedido_id);
$pedido = mysqli_fetch_assoc($result_pedido_id);
$pedido_id = $pedido['pedido_id'];

// Eliminar detalle del pedido
$query = "DELETE FROM detalle_pedidos WHERE id = $id";
mysqli_query($conexion, $query);

// Actualizar total del pedido
$query_total = "UPDATE pedidos SET total = (SELECT SUM(p.cantidad * pr.precio) FROM detalle_pedidos p JOIN productos pr ON p.producto_id = pr.id WHERE p.pedido_id = $pedido_id) WHERE id = $pedido_id";
mysqli_query($conexion, $query_total);

// Redirigir a la página de pedidos
header("Location: pedidos.php?mesa_id=" . $_GET['mesa_id']);

