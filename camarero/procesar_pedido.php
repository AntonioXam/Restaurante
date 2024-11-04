<?php
$mesa_id = $_POST['mesa_id'];
$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];
$notas = $_POST['notas'];

// Obtener el ID del pedido pendiente para la mesa
$pedido_query = "SELECT id FROM pedidos WHERE mesa_id = $mesa_id AND estado = 'pendiente'";
$pedido_result = mysqli_query($conexion, $pedido_query);
$pedido = mysqli_fetch_assoc($pedido_result);
$pedido_id = $pedido['id'];

// Insertar el detalle del pedido
$query = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, notas) VALUES ($pedido_id, $producto_id, $cantidad, '$notas')";
mysqli_query($conexion, $query);
?>
