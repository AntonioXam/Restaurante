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
            case 'pagar_sin_ticket':
                try {
                    // Procesar el pago sin generar ticket
                    $query = "SELECT c.*, p.nombre as nombre_producto 
                             FROM cuenta c 
                             INNER JOIN productos p ON c.producto_id = p.id 
                             WHERE c.mesa_id = ?";
                             
                    $stmt = mysqli_prepare($conexion, $query);
                    mysqli_stmt_bind_param($stmt, "i", $mesa_id);
                    mysqli_stmt_execute($stmt);
                    $productos_cuenta = mysqli_stmt_get_result($stmt);
                    
                    // Guardar cada producto en cuentas_pagadas para historial
                    while ($item = mysqli_fetch_assoc($productos_cuenta)) {
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
                    $_SESSION['error_ticket'] = "Error al procesar el pago: " . $e->getMessage();
                    header("Location: cuenta.php?mesa_id=$mesa_id&status=error");
                    exit;
                }
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
    <style>
    :root {
        --restaurant-primary: #2c3e50;    /* Azul oscuro principal */
        --restaurant-secondary: #34495e;   /* Azul oscuro secundario */
        --restaurant-accent: #3498db;      /* Azul claro para acentos */
        --restaurant-light: #ecf0f1;       /* Gris muy claro para fondos */
        --restaurant-dark: #1a252f;        /* Azul muy oscuro */
    }

    /* Estilos base */
    body {
        background-color: var(--restaurant-light);
    }

    /* Navegación y encabezados */
    .navbar {
        background: var(--restaurant-dark) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        background: var(--restaurant-primary) !important;
        color: white;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
    }

    /* Botones principales */
    .btn-primary {
        background-color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }

    .btn-primary:hover {
        background-color: var(--restaurant-secondary);
        border-color: var(--restaurant-secondary);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-outline-primary {
        color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }

    .btn-outline-primary:hover {
        background-color: var(--restaurant-primary);
        color: white;
    }

    /* Badges y elementos pequeños */
    .badge {
        padding: 0.5em 1em;
        border-radius: 4px;
    }

    .badge.bg-primary {
        background-color: var(--restaurant-primary) !important;
    }

    .badge.bg-secondary {
        background-color: var(--restaurant-accent) !important;
    }

    /* Botones de acción */
    .header-action-btn {
        border-color: white;
        color: white;
    }

    .header-action-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: white;
        color: white;
    }

    /* Tabla de productos */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(44, 62, 80, 0.05);
        transform: scale(1.01);
    }

    /* Botón de procesar pago */
    .btn-success {
        background-color: var(--restaurant-accent);
        border-color: var(--restaurant-accent);
    }

    .btn-success:hover {
        background-color: var(--restaurant-secondary);
        border-color: var(--restaurant-secondary);
        transform: translateY(-1px);
    }

    /* Modal */
    .modal-content {
        border-radius: 8px;
        border: none;
    }

    .modal-header {
        background: var(--restaurant-primary);
        color: white;
        border-radius: 8px 8px 0 0;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }

        .header-action-btn {
            min-width: 32px;
            height: 32px;
            padding: 0.25rem;
        }

        .header-action-btn span {
            display: none;
        }

        .header-action-btn i {
            margin: 0;
            font-size: 0.875rem;
        }

        .mobile-table td.action-buttons {
            display: flex;
            justify-content: flex-end;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
            border-top: 1px solid #dee2e6;
        }
    }

    </style>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-utensils me-2"></i>
                Restaurante
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 text-white">
                    <i class="fas fa-chair me-1"></i>
                    Mesa <?php echo $mesa_id; ?>
                </span>
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
    <?php if (isset($_SESSION['error_ticket'])): ?>
        <div class="alert alert-warning alert-dismissible fade show mx-3 mt-3" role="alert">
            <?php echo htmlspecialchars($_SESSION['error_ticket']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_ticket']); ?>
    <?php endif; ?>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-receipt me-2"></i>
                                <h5 class="card-title mb-0">Cuenta Mesa <?php echo $mesa_id; ?></h5>
                            </div>
                            <div>
                                <a href="generar_ticket.php?mesa_id=<?php echo $mesa_id; ?>" 
                                   class="btn btn-sm btn-outline-light header-action-btn">
                                    <i class="fas fa-receipt"></i>
                                    <span class="d-none d-sm-inline ms-1">Ticket Cuenta</span>
                                </a>
                                <!-- Eliminar el botón de Ticket Cocina -->
                                <!-- <form action="generar_ticket_cocina.php" method="GET" class="d-inline">
                                    <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-utensils me-2"></i>
                                        Ticket Cocina
                                    </button>
                                </form> -->
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
                                                        <button class="btn btn-danger" 
                                                                onclick="mostrarModalEliminar(<?php echo $item['id']; ?>, 
                                                                                              '<?php echo htmlspecialchars($item['nombre_producto']); ?>')">
                                                            <i class="fas fa-trash"></i>
                                                            <span class="d-none d-sm-inline ms-1">Eliminar</span>
                                                        </button>
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
                            <button type="button" class="btn btn-success w-100 mt-4" onclick="mostrarModalPago()">
                                <i class="fas fa-cash-register me-2"></i>
                                Procesar Pago
                            </button>
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
    :root {
        --restaurant-primary: #2c3e50;    /* Azul oscuro principal */
        --restaurant-secondary: #34495e;   /* Azul oscuro secundario */
        --restaurant-accent: #3498db;      /* Azul claro para acentos */
        --restaurant-light: #ecf0f1;       /* Gris muy claro para fondos */
        --restaurant-dark: #1a252f;        /* Azul muy oscuro */
    }

    /* Estilos base */
    body {
        background-color: var(--restaurant-light);
    }

    /* Navegación y encabezados */
    .navbar {
        background: var(--restaurant-dark) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        background: var(--restaurant-primary) !important;
        color: white;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
    }

    /* Botones principales */
    .btn-primary {
        background-color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }

    .btn-primary:hover {
        background-color: var (--restaurant-secondary);
        border-color: var(--restaurant-secondary);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-outline-primary {
        color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }

    .btn-outline-primary:hover {
        background-color: var(--restaurant-primary);
        color: white;
    }

    /* Badges y elementos pequeños */
    .badge {
        padding: 0.5em 1em;
        border-radius: 4px;
    }

    .badge.bg-primary {
        background-color: var(--restaurant-primary) !important;
    }

    .badge.bg-secondary {
        background-color: var(--restaurant-accent) !important;
    }

    /* Botones de acción */
    .header-action-btn {
        border-color: white;
        color: white;
    }

    .header-action-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: white;
        color: white;
    }

    /* Tabla de productos */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(44, 62, 80, 0.05);
        transform: scale(1.01);
    }

    /* Botón de procesar pago */
    .btn-success {
        background-color: var(--restaurant-accent);
        border-color: var(--restaurant-accent);
    }

    .btn-success:hover {
        background-color: var(--restaurant-secondary);
        border-color: var(--restaurant-secondary);
        transform: translateY(-1px);
    }

    /* Modal */
    .modal-content {
        border-radius: 8px;
        border: none;
    }

    .modal-header {
        background: var(--restaurant-primary);
        color: white;
        border-radius: 8px 8px 0 0;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }

        .header-action-btn {
            min-width: 32px;
            height: 32px;
            padding: 0.25rem;
        }

        .header-action-btn span {
            display: none;
        }

        .header-action-btn i {
            margin: 0;
            font-size: 0.875rem;
        }

        .mobile-table td.action-buttons {
            display: flex;
            justify-content: flex-end;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
            border-top: 1px solid #dee2e6;
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

    function mostrarModalPago() {
        new bootstrap.Modal(document.getElementById('pagoModal')).show();
    }

    function mostrarModalEliminar(itemId, nombreProducto) {
        document.getElementById('itemEliminarId').value = itemId;
        document.getElementById('productoEliminar').textContent = nombreProducto;
        new bootstrap.Modal(document.getElementById('eliminarModal')).show();
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

    <!-- Modal para confirmar pago -->
    <div class="modal fade" id="pagoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Total a pagar:</p>
                    <h3 class="text-center text-danger"><?php echo number_format($total, 2); ?>€</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST">
                        <input type="hidden" name="action" value="pagar">
                        <button type="submit" class="btn btn-success">Pagar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="eliminarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de eliminar <strong id="productoEliminar"></strong> de la cuenta?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="eliminar_cuenta">
                        <input type="hidden" name="item_id" id="itemEliminarId">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>