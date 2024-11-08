
<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;

// Obtener productos de la cuenta
$query = "SELECT c.*, p.nombre as nombre_producto 
         FROM cuenta c 
         INNER JOIN productos p ON c.producto_id = p.id 
         WHERE c.mesa_id = ? 
         ORDER BY c.fecha_hora ASC";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $mesa_id);
mysqli_stmt_execute($stmt);
$cuenta_result = mysqli_stmt_get_result($stmt);

// Calcular total
$total = 0;
$productos_cuenta = [];
while ($item = mysqli_fetch_assoc($cuenta_result)) {
    $total += $item['subtotal'];
    $productos_cuenta[] = $item;
}

// Procesar pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pagar') {
    try {
        mysqli_begin_transaction($conexion);
        
        // Limpiar cuenta y temp_ticket
        mysqli_query($conexion, "DELETE FROM cuenta WHERE mesa_id = $mesa_id");
        mysqli_query($conexion, "DELETE FROM temp_ticket WHERE mesa_id = $mesa_id");
        
        // Actualizar estado de la mesa
        mysqli_query($conexion, "UPDATE mesas SET estado = 'inactiva', comensales = NULL WHERE id = $mesa_id");
        
        mysqli_commit($conexion);
        header("Location: gestionar_mesas.php?status=success");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conexion);
        header("Location: cuenta.php?mesa_id=$mesa_id&status=error");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta Mesa <?php echo $mesa_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Cuenta Mesa <?php echo $mesa_id; ?></h5>
                        <a href="generar_ticket.php?mesa_id=<?php echo $mesa_id; ?>" class="btn btn-primary">
                            Generar Ticket PDF
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productos_cuenta)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos_cuenta as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                                                <td><?php echo $item['cantidad']; ?></td>
                                                <td><?php echo number_format($item['precio_unitario'], 2); ?>€</td>
                                                <td><?php echo number_format($item['subtotal'], 2); ?>€</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td><strong><?php echo number_format($total, 2); ?>€</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <form method="POST" class="mt-3">
                                <input type="hidden" name="action" value="pagar">
                                <button type="submit" class="btn btn-success" onclick="return confirm('¿Confirmar pago?')">
                                    Procesar Pago
                                </button>
                            </form>
                        <?php else: ?>
                            <p class="text-center">No hay productos en la cuenta</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>