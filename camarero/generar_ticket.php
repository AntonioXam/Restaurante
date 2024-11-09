<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;

if (!$mesa_id) {
    header("Location: gestionar_pedido.php");
    exit;
}

// Obtener los productos de la cuenta
$query = "SELECT c.*, p.nombre as nombre_producto 
         FROM cuenta c 
         INNER JOIN productos p ON c.producto_id = p.id 
         WHERE c.mesa_id = ? 
         ORDER BY c.fecha_hora DESC";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $mesa_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Preparar los datos para el ticket
$productos = [];
$total = 0;

while ($item = mysqli_fetch_assoc($resultado)) {
    $productos[] = [
        'nombre' => $item['nombre_producto'],
        'cantidad' => $item['cantidad'],
        'precio' => $item['precio_unitario'],
        'subtotal' => $item['subtotal']
    ];
    $total += $item['subtotal'];
}

// Obtener información de la mesa
$mesa_query = "SELECT numero_mesa FROM mesas WHERE id = ?";
$stmt = mysqli_prepare($conexion, $stmt_mesa);
mysqli_stmt_bind_param($stmt_mesa, "i", $mesa_id);
mysqli_stmt_execute($stmt_mesa);
$mesa_result = mysqli_stmt_get_result($stmt_mesa);
$mesa = mysqli_fetch_assoc($mesa_result);

// Guardar los datos en la sesión para accederlos desde ethernet.php
$_SESSION['ticket_data'] = [
    'mesa_numero' => $mesa['numero_mesa'],
    'productos' => $productos,
    'total' => $total,
    'fecha' => date('Y-m-d H:i:s')
];

// Redirigir a ethernet.php para imprimir
header("Location: ../imprimir_tickets/example/interface/ethernet.php");
exit;
?>
