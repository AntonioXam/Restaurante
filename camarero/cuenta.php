<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;

// Mostrar mensaje de error si existe
if (isset($_SESSION['error_ticket'])) {
    $error_mensaje = $_SESSION['error_ticket'];
    unset($_SESSION['error_ticket']);
}

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

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        mysqli_begin_transaction($conexion);
        
        switch ($_POST['action']) {
            case 'modificar_cuenta':
                $item_id = (int)$_POST['item_id'];
                $cantidad = (int)$_POST['cantidad'];
                $precio_unitario = (float)$_POST['precio_unitario'];
                $subtotal = $cantidad * $precio_unitario;
                
                mysqli_query($conexion, "UPDATE cuenta 
                                       SET cantidad = $cantidad, 
                                           subtotal = $subtotal 
                                       WHERE id = $item_id");
                break;
                
            case 'eliminar_cuenta':
                $item_id = (int)$_POST['item_id'];
                mysqli_query($conexion, "DELETE FROM cuenta WHERE id = $item_id");
                break;
                
            case 'pagar':
                try {
                    // Primero generamos el ticket
                    header("Location: generar_ticket.php?mesa_id=$mesa_id&action=pagar");
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error_ticket'] = "Error al generar ticket: " . $e->getMessage();
                    header("Location: cuenta.php?mesa_id=$mesa_id&status=error");
                    exit;
                }
                break;
        }
        
        mysqli_commit($conexion);
        header("Location: cuenta.php?mesa_id=$mesa_id&status=success");
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

    <?php if (isset($error_mensaje)): ?>
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
            <?php echo htmlspecialchars($error_mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

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
                                                    <button type="button" class="btn btn-link p-0" 
                                                            onclick="modificarCantidad(<?php echo $item['id']; ?>, 
                                                                                    '<?php echo htmlspecialchars($item['nombre_producto']); ?>', 
                                                                                    <?php echo $item['cantidad']; ?>, 
                                                                                    <?php echo $item['precio_unitario']; ?>)">
                                                        <span class="badge bg-secondary">
                                                            <?php echo $item['cantidad']; ?>
                                                        </span>
                                                    </button>
                                                </td>
                                                <td class="text-end precio-cell">
                                                    <?php echo number_format($item['precio_unitario'], 2); ?>€
                                                </td>
                                                <td class="text-end subtotal-cell">
                                                    <?php echo number_format($item['subtotal'], 2); ?>€
                                                </td>
                                                <td class="action-buttons text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-warning" 
                                                                onclick="modificarCantidad(<?php echo $item['id']; ?>, 
                                                                                       '<?php echo htmlspecialchars($item['nombre_producto']); ?>', 
                                                                                       <?php echo $item['cantidad']; ?>,
                                                                                       <?php echo $item['precio_unitario']; ?>)">
                                                            <i class="fas fa-edit"></i>
                                                            <span class="d-none d-sm-inline ms-1">Modificar</span>
                                                        </button>
                                                        <form action="" method="POST" class="d-inline">
                                                            <input type="hidden" name="action" value="eliminar_cuenta">
                                                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                            <button type="submit" class="btn btn-danger" 
                                                                    onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                                <i class="fas fa-trash"></i>
                                                                <span class="d-none d-sm-inline ms-1">Eliminar</span>
                                                            </button>
                                                        </form>
                                                    </div>
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

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .cantidad-cell .btn-link {
        text-decoration: none;
    }

    .cantidad-cell .btn-link:hover .badge {
        background-color: #0d6efd !important;
    }

    @media (max-width: 576px) {
        .btn-group-sm .btn {
            padding: 0.2rem 0.4rem;
        }
        
        .btn-group-sm .btn i {
            font-size: 0.8rem;
        }
    }

    /* Estilos para los botones de acción */
    .action-buttons .btn-group {
        gap: 0.25rem;
    }

    .action-buttons .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.75rem;
    }

    .action-buttons .btn i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1em;
        height: 1em;
    }

    @media (max-width: 576px) {
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
        }
        
        .action-buttons .btn i {
            font-size: 0.875rem;
        }
        
        .mobile-table td.action-buttons {
            display: flex;
            justify-content: flex-end;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
            border-top: 1px solid #dee2e6;
        }
        
        .btn-group {
            display: flex;
            gap: 0.25rem;
        }
        
        .btn-group .btn {
            flex: 1;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function modificarCantidad(itemId, nombre, cantidad, precio) {
        document.getElementById('mod_item_id').value = itemId;
        document.getElementById('mod_producto_nombre').textContent = nombre;
        document.getElementById('mod_cantidad').value = cantidad;
        document.getElementById('mod_precio_unitario').value = precio;
        new bootstrap.Modal(document.getElementById('modificarModal')).show();
    }

    function ajustarCantidad(cambio) {
        const input = document.getElementById('mod_cantidad');
        const nuevoValor = parseInt(input.value) + cambio;
        if (nuevoValor >= 1) {
            input.value = nuevoValor;
        }
    }

    function eliminarProducto(itemId, nombre) {
        if (confirm(`¿Está seguro de eliminar ${nombre} de la cuenta?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="eliminar_cuenta">
                <input type="hidden" name="item_id" value="${itemId}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>

    <!-- Añadir el modal de modificación antes del cierre del body -->
    <div class="modal fade" id="modificarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Cantidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="modificar_cuenta">
                        <input type="hidden" name="item_id" id="mod_item_id">
                        <input type="hidden" name="precio_unitario" id="mod_precio_unitario">
                        <h6 id="mod_producto_nombre" class="mb-3"></h6>
                        <div class="form-group">
                            <label class="form-label">Cantidad:</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="ajustarCantidad(-1)">-</button>
                                <input type="number" name="cantidad" id="mod_cantidad" 
                                       class="form-control text-center" min="1" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="ajustarCantidad(1)">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>