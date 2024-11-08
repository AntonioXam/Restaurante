<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;

// Modificar la consulta para obtener los productos correctamente
$query = "SELECT c.*, p.nombre as nombre_producto 
         FROM cuenta c 
         INNER JOIN productos p ON c.producto_id = p.id 
         WHERE c.mesa_id = ? 
         ORDER BY c.fecha_hora DESC";

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

// Modificar la sección de procesar pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pagar') {
    try {
        mysqli_begin_transaction($conexion);
        
        // Guardar cada producto en cuentas_pagadas para historial
        foreach ($productos_cuenta as $item) {
            $insert_cuenta = "INSERT INTO cuentas_pagadas 
                            (mesa_id, producto, cantidad, precio_unitario, subtotal) 
                            VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $insert_cuenta);
            mysqli_stmt_bind_param($stmt, "isids",
                $mesa_id,
                $item['nombre_producto'],
                $item['cantidad'],
                $item['precio_unitario'],
                $item['subtotal']
            );
            mysqli_stmt_execute($stmt);
        }
        
        // Limpiar cuenta actual y liberar mesa
        mysqli_query($conexion, "DELETE FROM cuenta WHERE mesa_id = $mesa_id");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Mesa <?php echo $mesa_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Restaurante</a>
            <div class="d-flex align-items-center">
                <a href="gestionar_pedido.php?mesa_id=<?php echo $mesa_id; ?>" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Cuenta Mesa <?php echo $mesa_id; ?>
                            </h5>
                            <div class="btn-group">
                                <a href="generar_ticket.php?mesa_id=<?php echo $mesa_id; ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-file-pdf me-2"></i>
                                    Generar Ticket
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productos_cuenta)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mobile-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Producto</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-end">Precio</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos_cuenta as $item): ?>
                                            <tr>
                                                <td class="producto-cell">
                                                    <?php echo htmlspecialchars($item['nombre_producto']); ?>
                                                </td>
                                                <td class="text-center cantidad-cell">
                                                    <span class="badge bg-secondary">
                                                        <?php echo $item['cantidad']; ?>
                                                    </span>
                                                </td>
                                                <td class="text-end precio-cell">
                                                    <?php echo number_format($item['precio_unitario'], 2); ?>€
                                                </td>
                                                <td class="text-end subtotal-cell">
                                                    <?php echo number_format($item['subtotal'], 2); ?>€
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="3" class="text-end">
                                                <strong>Total:</strong>
                                            </td>
                                            <td class="text-end">
                                                <strong><?php echo number_format($total, 2); ?>€</strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <form method="POST" class="mt-4">
                                <input type="hidden" name="action" value="pagar">
                                <button type="submit" 
                                        class="btn btn-success w-100" 
                                        onclick="return confirm('¿Confirmar pago?')">
                                    <i class="fas fa-cash-register me-2"></i>
                                    Procesar Pago
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay productos en la cuenta</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Actualizar estilos existentes y agregar nuevos */
    @media (max-width: 576px) {
        .mobile-table tbody tr {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #dee2e6;
            padding: 0.5rem 0;
        }

        .mobile-table td {
            border: none;
            padding: 0.25rem 0.5rem;
        }

        .producto-cell {
            width: 100%;
            font-weight: bold;
        }

        .cantidad-cell, .precio-cell {
            width: 50%;
            text-align: center;
        }

        .subtotal-cell {
            width: 100%;
            text-align: right;
            font-weight: bold;
            border-top: 1px dashed #dee2e6;
            margin-top: 0.25rem;
            padding-top: 0.25rem;
        }

        .table thead {
            display: none;
        }

        .table tfoot tr td {
            width: 100%;
            text-align: right;
            padding: 0.75rem;
            font-size: 1.1rem;
        }

        .btn-group {
            width: 100%;
        }

        .btn-group .btn {
            flex: 1;
        }
    }

    .badge {
        min-width: 2rem;
        padding: 0.35em 0.65em;
    }

    /* Mejoras visuales generales */
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .btn-success {
        padding: 0.75rem;
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>