<?php
include '../sesion.php';
include '../conexion.php';

// Obtener el ID del pedido
$id = $_GET['id'];

// Eliminar detalle del pedido
$query = "DELETE FROM detalle_pedidos WHERE id = $id";
mysqli_query($conexion, $query);

// Actualizar total del pedido
$query_total = "UPDATE pedidos SET total = (SELECT SUM(p.cantidad * pr.precio) FROM detalle_pedidos p JOIN productos pr ON p.producto_id = pr.id WHERE p.pedido_id = (SELECT pedido_id FROM detalle_pedidos WHERE id = $id)) WHERE id = (SELECT pedido_id FROM detalle_pedidos WHERE id = $id)";
mysqli_query($conexion, $query_total);

// Redirigir a la página de pedidos
header("Location: pedidos.php?mesa_id=" . $_GET['mesa_id']);
