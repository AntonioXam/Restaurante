<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;

try {
    if (!$mesa_id) {
        throw new Exception("ID de mesa no válido");
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

    if (mysqli_num_rows($resultado) == 0) {
        throw new Exception("No hay productos en la cuenta");
    }

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
    $stmt_mesa = mysqli_prepare($conexion, $mesa_query);
    if (!$stmt_mesa) {
        throw new Exception("Error preparando la consulta de mesa");
    }
    
    mysqli_stmt_bind_param($stmt_mesa, "i", $mesa_id);
    mysqli_stmt_execute($stmt_mesa);
    $mesa_result = mysqli_stmt_get_result($stmt_mesa);
    $mesa = mysqli_fetch_assoc($mesa_result);

    if (!$mesa) {
        throw new Exception("No se encontró la mesa especificada");
    }

    // Guardar los datos en la sesión para accederlos desde ethernet.php
    $_SESSION['ticket_data'] = [
        'mesa_numero' => $mesa['numero_mesa'],
        'productos' => $productos,
        'total' => $total,
        'fecha' => date('Y-m-d H:i:s'),
        'mesa_id' => $mesa_id,
        'return_url' => 'cuenta.php' // URL de retorno en caso de error
    ];

    // Redirigir a ethernet.php para imprimir
    header("Location: ../imprimir_tickets/example/interface/ethernet.php");
    exit;

} catch (Exception $e) {
    $_SESSION['error_ticket'] = "Error al generar ticket: " . $e->getMessage();
    header("Location: cuenta.php?mesa_id=$mesa_id&status=error");
    exit;
}
?>
