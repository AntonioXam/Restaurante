<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? (int)$_GET['mesa_id'] : null;

// Variables principales:
// $mesa_id - ID de la mesa seleccionada
// $mesa - Datos de la mesa actual
// $detalle_pedidos_result - Resultado de productos en el pedido
// $total - Total acumulado del pedido

/**
 * Obtiene los datos de una mesa específica
 * @param mysqli $conexion - Conexión a la base de datos
 * @param int $mesa_id - ID de la mesa a consultar
 * @return array - Datos de la mesa
 */
function obtener_mesa($conexion, $mesa_id) {
    $query = "SELECT * FROM mesas WHERE id = $mesa_id";
    return mysqli_fetch_assoc(mysqli_query($conexion, $query));
}

/**
 * Obtiene los detalles de los pedidos pendientes de una mesa
 * @param mysqli $conexion - Conexión a la base de datos
 * @param int $mesa_id - ID de la mesa a consultar
 * @return mysqli_result - Resultado de la consulta
 */
function obtener_detalle_pedidos($conexion, $mesa_id) {
    $query = "SELECT dp.*, p.nombre as nombre_producto, p.precio 
              FROM detalle_pedidos dp 
              INNER JOIN productos p ON dp.producto_id = p.id 
              INNER JOIN pedidos ped ON dp.pedido_id = ped.id 
              WHERE ped.mesa_id = $mesa_id AND ped.estado = 'pendiente'";
    return mysqli_query($conexion, $query);
}

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'agregar':
                // Verificar si existe un pedido pendiente para la mesa
                $pedido_query = "SELECT id FROM pedidos WHERE mesa_id = $mesa_id AND estado = 'pendiente'";
                $pedido_result = mysqli_query($conexion, $pedido_query);
                
                // Si no existe pedido pendiente, crear uno nuevo
                if (mysqli_num_rows($pedido_result) == 0) {
                    mysqli_query($conexion, "INSERT INTO pedidos (mesa_id, estado, total) VALUES ($mesa_id, 'pendiente', 0)");
                    $pedido_id = mysqli_insert_id($conexion);
                } else {
                    $pedido = mysqli_fetch_assoc($pedido_result);
                    $pedido_id = $pedido['id'];
                }
                
                // Insertar el nuevo detalle de pedido
                $producto_id = mysqli_real_escape_string($conexion, $_POST['producto_id']);
                $cantidad = (int)$_POST['cantidad'];
                $notas = mysqli_real_escape_string($conexion, isset($_POST['notas']) ? $_POST['notas'] : '');
                
                $query = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, notas) 
                         VALUES ($pedido_id, $producto_id, $cantidad, '$notas')";
                
                if (!mysqli_query($conexion, $query)) {
                    // Manejo de error si es necesario
                    echo "Error: " . mysqli_error($conexion);
                }

            case 'eliminar':
                $detalle_id = mysqli_real_escape_string($conexion, $_POST['detalle_id']);
                mysqli_query($conexion, "DELETE FROM detalle_pedidos WHERE id = '$detalle_id'");
                break;
                
            case 'modificar':
                $detalle_id = $_POST['detalle_id'];
                $nueva_cantidad = $_POST['cantidad'];
                $nuevas_notas = mysqli_real_escape_string($conexion, $_POST['notas']);
                mysqli_query($conexion, "UPDATE detalle_pedidos 
                                       SET cantidad = $nueva_cantidad, 
                                           notas = '$nuevas_notas' 
                                       WHERE id = $detalle_id");
                break;
                
            case 'enviar_cocina':
                mysqli_begin_transaction($conexion);
                
                // 1. Obtener todos los productos del pedido pendiente
                $query = "SELECT dp.*, p.nombre as nombre_producto, p.precio 
                         FROM detalle_pedidos dp 
                         INNER JOIN productos p ON dp.producto_id = p.id 
                         INNER JOIN pedidos ped ON dp.pedido_id = ped.id 
                         WHERE ped.mesa_id = ? AND ped.estado = 'pendiente'";
                
                $stmt = mysqli_prepare($conexion, $query);
                mysqli_stmt_bind_param($stmt, "i", $mesa_id);
                mysqli_stmt_execute($stmt);
                $detalles = mysqli_stmt_get_result($stmt);
                
                // Guardar los resultados en un array para usarlos múltiples veces
                $productos = [];
                while ($row = mysqli_fetch_assoc($detalles)) {
                    $productos[] = $row;
                }

                if (empty($productos)) {
                    mysqli_rollback($conexion);
                    $_SESSION['error_ticket'] = "No hay productos para enviar a cocina";
                    header("Location: gestionar_pedido.php?mesa_id=$mesa_id&error=true");
                    exit;
                }

                try {
                    // Procesar la cuenta primero
                    foreach ($productos as $detalle) {
                        $subtotal = $detalle['cantidad'] * $detalle['precio'];

                        // Verificar si ya existe en la cuenta
                        $check_query = "SELECT id, cantidad FROM cuenta 
                                      WHERE mesa_id = ? AND producto_id = ?";
                        $check_stmt = mysqli_prepare($conexion, $check_query);
                        mysqli_stmt_bind_param($check_stmt, "ii", $mesa_id, $detalle['producto_id']);
                        mysqli_stmt_execute($check_stmt);
                        $existing = mysqli_stmt_get_result($check_stmt)->fetch_assoc();

                        if ($existing) {
                            // Actualizar cantidad y subtotal existente
                            $nueva_cantidad = $existing['cantidad'] + $detalle['cantidad'];
                            $nuevo_subtotal = $nueva_cantidad * $detalle['precio'];
                            $update = "UPDATE cuenta 
                                      SET cantidad = ?, subtotal = ? 
                                      WHERE id = ?";
                            $update_stmt = mysqli_prepare($conexion, $update);
                            mysqli_stmt_bind_param($update_stmt, "idi", 
                                $nueva_cantidad, 
                                $nuevo_subtotal, 
                                $existing['id']
                            );
                            mysqli_stmt_execute($update_stmt);
                        } else {
                            // Insertar nuevo producto en cuenta
                            $insert = "INSERT INTO cuenta 
                                      (mesa_id, producto_id, cantidad, precio_unitario, subtotal) 
                                      VALUES (?, ?, ?, ?, ?)";
                            $insert_stmt = mysqli_prepare($conexion, $insert);
                            mysqli_stmt_bind_param($insert_stmt, "iiidd",
                                $mesa_id,
                                $detalle['producto_id'],
                                $detalle['cantidad'],
                                $detalle['precio'],
                                $subtotal
                            );
                            mysqli_stmt_execute($insert_stmt);
                        }
                    }
                    // Actualizar el estado del pedido a completado
                    mysqli_query($conexion, "UPDATE pedidos 
                                           SET estado = 'completado' 
                                           WHERE mesa_id = $mesa_id AND estado = 'pendiente'");

                    // Intentar generar el ticket de cocina
                    require_once 'generar_ticket_cocina.php';
                    $ticket_generado = generar_ticket_cocina($conexion, $mesa_id, $productos);

                    if (!$ticket_generado) {
                        error_log("Error al imprimir ticket de cocina para mesa $mesa_id");
                        $_SESSION['error_ticket'] = "Error al imprimir ticket de cocina. Los productos se han añadido a la cuenta.";
                    }

                    // Confirmar la transacción
                    mysqli_commit($conexion);

                } catch (Exception $e) {
                    // En caso de error, revertir la transacción y mostrar mensaje de error
                    mysqli_rollback($conexion);
                    $_SESSION['error_ticket'] = "Error al procesar el pedido: " . $e->getMessage();
                    header("Location: cuenta.php?mesa_id=$mesa_id");
                    exit;
                }

                // Redirigir a la página de cuenta
                header("Location: cuenta.php?mesa_id=$mesa_id");
                exit;
               
        }
        header("Location: gestionar_pedido.php?mesa_id=$mesa_id");
        exit;
    }
}

$mesa = $mesa_id ? obtener_mesa($conexion, $mesa_id) : null;
$detalle_pedidos_result = $mesa_id ? obtener_detalle_pedidos($conexion, $mesa_id) : null;

$mesa_numero = isset($mesa) ? $mesa['numero_mesa'] : 'No seleccionada';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
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
    .navbar, .card-header {
        background: var(--restaurant-primary) !important;
        color: white;
    }

    /* Tarjetas y contenedores */
    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    /* Navegación por pestañas */
    .nav-container {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .nav-pills .nav-link {
        color: var(--restaurant-dark);
        border-radius: 6px;
        padding: 0.5rem 1.2rem;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background-color: var(--restaurant-primary);
        color: white;
        box-shadow: 0 2px 4px rgba(52, 152, 219, 0.2);
    }

    .nav-pills .nav-link:hover {
        background-color: rgba(52, 152, 219, 0.1);
        transform: translateY(-1px);
    }

    /* Productos */
    .product-item {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.08);
    }

    .product-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Botones */
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

    .add-btn {
        background-color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        background-color: var(--restaurant-secondary);
        transform: scale(1.1);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    /* Badges y elementos pequeños */
    .badge {
        padding: 0.5em 1em;
        border-radius: 4px;
    }

    .badge.bg-primary {
        background-color: var(--restaurant-primary) !important;
    }

    /* Tablas */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(52, 152, 219, 0.05);
        transform: scale(1.01);
    }

    /* Botones de acción en tabla */
    .action-buttons .btn {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .action-buttons .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Modales */
    .modal-content {
        border-radius: 8px;
        border: none;
    }

    .modal-header {
        background: var(--restaurant-primary);
        color: white;
        border-radius: 8px 8px 0 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .nav-container {
            margin: 0 -1rem;
            border-radius: 0;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .product-item {
            margin-bottom: 0.5rem;
        }
    }

    /* Ajustes específicos de categorías */
    .hide-scrollbar {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .nav-pills {
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 0.5rem;
        padding: 0.5rem;
    }

    .products-header {
        padding: 1.5rem;
        background: var(--restaurant-primary);
        color: white;
        border-radius: 8px 8px 0 0;
        margin-bottom: 1rem;
    }

    .products-header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .scroll-hint {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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
                <?php if(isset($mesa) && $mesa): ?>
                    <span class="navbar-text me-3 text-white">
                        <i class="fas fa-chair me-1"></i>
                        Mesa <?php echo htmlspecialchars($mesa_numero); ?>
                    </span>
                    <a href="cuenta.php?mesa_id=<?php echo $mesa_id; ?>" 
                       class="btn btn-outline-success btn-sm me-2">
                        <i class="fas fa-receipt"></i>
                        <span class="d-none d-sm-inline ms-1">Cuenta</span>
                    </a>
                <?php endif; ?>
                <a href="gestionar_mesas.php" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container-fluid py-3">
        <?php if (!$mesa_id): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Mesas Activas</h5>
                            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
                                <?php
                                $mesas_query = "SELECT * FROM mesas WHERE estado = 'activa' ORDER BY numero_mesa";
                                $mesas_result = mysqli_query($conexion, $mesas_query);
                                while ($mesa = mysqli_fetch_assoc($mesas_result)):
                                ?>
                                <div class="col">
                                    <a href="?mesa_id=<?php echo $mesa['id']; ?>" class="text-decoration-none">
                                        <div class="card h-100 mesa-card">
                                            <div class="card-body text-center">
                                                <i class="fas fa-chair fa-2x mb-2"></i>
                                                <h5 class="card-title mb-2">Mesa <?php echo $mesa['numero_mesa']; ?></h5>
                                                <p class="card-text mb-0">
                                                    <small class="text-muted">
                                                        <?php echo $mesa['comensales']; ?> comensales
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <?php if (mysqli_num_rows($mesas_result) == 0): ?>
                                <p class="text-center text-muted mt-4">No hay mesas activas</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Sección de Productos por Categorías -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="products-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5>
                                    <i class="fas fa-utensils me-2"></i>
                                    Añadir Productos
                                </h5>
                                <span class="scroll-hint">
                                    <i class="fas fa-arrows-alt-h"></i>
                                    <span class="d-none d-sm-inline">Desliza para</span>
                                    <span>más categorías</span>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Nav pills scrollable -->
                            <div class="nav-container mb-3">
                                <ul class="nav nav-pills flex-nowrap hide-scrollbar" id="productTabs" role="tablist">
                                    <?php
                                    $query_categorias = "SELECT DISTINCT categoria FROM productos ORDER BY categoria";
                                    $result_categorias = mysqli_query($conexion, $query_categorias);
                                    $first = true;
                                    while ($cat = mysqli_fetch_assoc($result_categorias)):
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo $first ? 'active' : ''; ?>" 
                                           id="<?php echo $cat['categoria']; ?>-tab" 
                                           data-bs-toggle="pill"
                                           href="#<?php echo $cat['categoria']; ?>">
                                            <?php echo ucfirst($cat['categoria']); ?>
                                        </a>
                                    </li>
                                    <?php 
                                    $first = false;
                                    endwhile; 
                                    ?>
                                </ul>
                            </div>

                            <!-- Contenido de las pestañas -->
                            <div class="tab-content" id="productTabContent">
                                <?php 
                                mysqli_data_seek($result_categorias, 0);
                                $first = true;
                                while ($cat = mysqli_fetch_assoc($result_categorias)): 
                                ?>
                                <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" 
                                     id="<?php echo $cat['categoria']; ?>">
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                        <?php
                                        $query = "SELECT * FROM productos WHERE categoria = '{$cat['categoria']}'";
                                        $productos = mysqli_query($conexion, $query);
                                        while ($producto = mysqli_fetch_assoc($productos)):
                                        ?>
                                        <div class="col">
                                            <div class="list-group-item product-item d-flex justify-content-between align-items-center rounded">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($producto['nombre']); ?></h6>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-primary me-2"><?php echo number_format($producto['precio'], 2); ?>€</span>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary btn-sm rounded-circle add-btn" 
                                                        onclick="mostrarModalAgregar(<?php echo $producto['id']; ?>, 
                                                                                '<?php echo htmlspecialchars($producto['nombre']); ?>', 
                                                                                <?php echo $producto['precio']; ?>)">
                                                    <i class="fas fa-plus fa-fw"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php 
                                $first = false;
                                endwhile; 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para agregar producto -->
            <div class="modal fade" id="agregarModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Añadir Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="action" value="agregar">
                                <input type="hidden" name="producto_id" id="add_producto_id">
                                <h6 id="producto_nombre" class="mb-2"></h6>
                                <p class="text-muted mb-3">Precio: <span id="producto_precio"></span>€</p>
                                <div class="form-group mb-3">
                                    <label>Cantidad:</label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="ajustarCantidad(-1)">-</button>
                                        <input type="number" name="cantidad" id="add_cantidad" class="form-control text-center" value="1" min="1" required>
                                        <button type="button" class="btn btn-outline-secondary" onclick="ajustarCantidad(1)">+</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Notas:</label>
                                    <textarea name="notas" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Añadir al Pedido</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Productos Añadidos -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Productos en el Pedido</h5>
                            <?php if ($detalle_pedidos_result && mysqli_num_rows($detalle_pedidos_result) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mobile-table">
                                        <thead>
                                            <tr>
                                                <th class="col-4 col-sm-3">Producto</th>
                                                <th class="col-2 col-sm-2">Cant.</th>
                                                <th class="col-3 col-sm-2">Precio</th>
                                                <th class="d-none d-md-table-cell">Subtotal</th>
                                                <th class="d-none d-md-table-cell">Notas</th>
                                                <th class="col-3 col-sm-5">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total = 0;
                                            while ($detalle = mysqli_fetch_assoc($detalle_pedidos_result)): 
                                                $subtotal = $detalle['cantidad'] * $detalle['precio'];
                                                $total += $subtotal;
                                            ?>
                                            <tr class="producto-row">
                                                <td class="producto-cell">
                                                    <div class="text-truncate">
                                                        <?php echo htmlspecialchars($detalle['nombre_producto']); ?>
                                                    </div>
                                                </td>
                                                <td class="cantidad-cell text-center">
                                                    <span class="badge bg-secondary">
                                                        <?php echo $detalle['cantidad']; ?>
                                                    </span>
                                                </td>
                                                <td class="precio-cell text-end">
                                                    <?php echo number_format($detalle['precio'], 2); ?>€
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <?php echo number_format($subtotal, 2); ?>€
                                                </td>
                                                <td class="d-none d-md-table-cell notes-cell">
                                                    <?php if ($detalle['notas']): ?>
                                                        <small class="text-muted"><?php echo htmlspecialchars($detalle['notas']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="action-buttons text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-warning" 
                                                                onclick="modificarCantidad(<?php echo $detalle['id']; ?>, 
                                                                                         <?php echo $detalle['cantidad']; ?>, 
                                                                                         '<?php echo addslashes($detalle['notas']); ?>')">
                                                            <i class="fas fa-edit"></i>
                                                            <span class="d-none d-sm-inline ms-1">Modificar</span>
                                                        </button>
                                                        <button class="btn btn-danger" 
                                                                onclick="mostrarModalEliminar(<?php echo $detalle['id']; ?>, 
                                                                                              '<?php echo htmlspecialchars($detalle['nombre_producto']); ?>')">
                                                            <i class="fas fa-trash"></i>
                                                            <span class="d-none d-sm-inline ms-1">Eliminar</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="total-row">
                                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                                <td colspan="4" class="text-start"><strong><?php echo number_format($total, 2); ?>€</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    
                                    <!-- Botón Enviar a Cocina -->
                                    <div class="text-end mt-3">
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="enviar_cocina">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-utensils me-2"></i>
                                                Enviar a Cocina
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No hay productos en el pedido</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal para modificar cantidad y notas -->
    <div class="modal fade" id="modificarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="modificar">
                        <input type="hidden" name="detalle_id" id="mod_detalle_id">
                        <div class="form-group mb-3">
                            <label>Cantidad:</label>
                            <input type="number" name="cantidad" id="mod_cantidad" class="form-control" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Notas:</label>
                            <textarea name="notas" id="mod_notas" class="form-control" rows="3"></textarea>
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

    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="eliminarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de eliminar <strong id="productoEliminar"></strong> del pedido?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="eliminar">
                        <input type="hidden" name="detalle_id" id="detalleEliminarId">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function modificarCantidad(detalle_id, cantidad, notas) {
        document.getElementById('mod_detalle_id').value = detalle_id;
        document.getElementById('mod_cantidad').value = cantidad;
        document.getElementById('mod_notas').value = notas;
        new bootstrap.Modal(document.getElementById('modificarModal')).show();
    }

    function mostrarModalAgregar(producto_id, nombre, precio) {
        document.getElementById('add_producto_id').value = producto_id;
        document.getElementById('producto_nombre').textContent = nombre;
        document.getElementById('producto_precio').textContent = precio.toFixed(2);
        document.getElementById('add_cantidad').value = 1;
        new bootstrap.Modal(document.getElementById('agregarModal')).show();
    }

    function ajustarCantidad(cambio) {
        const input = document.getElementById('add_cantidad');
        const nuevoValor = parseInt(input.value) + cambio;
        if (nuevoValor >= 1) {
            input.value = nuevoValor;
        }
    }

    function mostrarModalEliminar(detalleId, nombreProducto) {
        document.getElementById('detalleEliminarId').value = detalleId;
        document.getElementById('productoEliminar').textContent = nombreProducto;
        new bootstrap.Modal(document.getElementById('eliminarModal')).show();
    }
    </script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    .mesa-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    
    .mesa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .mesa-card .card-body {
        padding: 1.25rem;
    }
    
    .mesa-card .fa-chair {
        color: #0d6efd;
    }
    
    @media (max-width: 576px) {
        .mesa-card .card-body {
            padding: 1rem;
        }
        
        .mesa-card .fa-2x {
            font-size: 1.5em;
        }
        
        .mesa-card .card-title {
            font-size: 1rem;
        }
        
        .mesa-card .card-text {
            font-size: 0.8rem;
        }
    }

    /* Estilos para la tabla en móviles */
    @media (max-width: 768px) {
        .mobile-table {
            border: 0;
        }

        .mobile-table thead {
            display: none;
        }

        .mobile-table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 0.5rem;
            background: #fff;
        }

        .mobile-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.5rem;
            text-align: right;
        }

        .mobile-table td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 1rem;
            text-align: left;
        }

        .mobile-table td.action-buttons {
            flex-direction: row;
            justify-content: space-evenly;
            gap: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }

        .btn-action {
            width: 45%;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .notes-cell {
            max-width: 100%;
        }

        .total-row td {
            justify-content: flex-end;
            font-size: 1.1rem;
        }

        .total-row td::before {
            font-size: 1.1rem;
        }
    }

    /* Mejoras para los botones en móvil */
    .btn-action {
        border-radius: 0.5rem;
        transition: transform 0.1s;
    }

    .btn-action:active {
        transform: scale(0.95);
    }

    .btn-action i {
        margin-right: 0.25rem;
    }

    /* Ajustes para el modal en móvil */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .modal-body {
            padding: 1rem;
        }
    }

    /* Estilos para los botones en móviles */
    @media (max-width: 576px) {
        .btn-action {
            width: auto !important;
            padding: 0.5rem !important;
            min-width: 40px;
        }
        
        .action-buttons {
            gap: 0.25rem !important;
        }
        
        .btn-action i {
            margin-right: 0 !important;
        }
        
        .mobile-table td.action-buttons {
            justify-content: flex-end;
        }
    }

    /* Estilos específicos para móviles pequeños */
    @media (max-width: 576px) {
        .mobile-table tr {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            margin-bottom: 0.5rem !important;
            padding: 0.5rem !important;
            gap: 0.5rem;
        }

        .mobile-table td {
            padding: 0 !important;
            border: none !important;
            font-size: 0.9rem;
        }

        .mobile-table td::before {
            display: none !important;
        }

        .producto-cell {
            flex: 1 1 50% !important;
            min-width: 0;
        }

        .cantidad-cell {
            flex: 0 0 auto !important;
        }

        .precio-cell {
            flex: 0 0 auto !important;
        }

        .action-buttons {
            flex: 1 1 100% !important;
            display: flex;
            justify-content: flex-end;
            padding-top: 0.5rem !important;
            margin-top: 0.5rem;
            border-top: 1px solid #dee2e6;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .btn-group {
            gap: 0.25rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .text-truncate {
            max-width: 150px;
        }
    }

    /* Ajustes adicionales para pantallas muy pequeñas */
    @media (max-width: 360px) {
        .producto-cell {
            flex: 1 1 100% !important;
        }

        .cantidad-cell,
        .precio-cell {
            flex: 0 0 auto !important;
        }
    }

    /* Ajustes para pantalla SM */
    @media (min-width: 576px) and (max-width: 767.98px) {
        .mobile-table tr {
            flex-wrap: nowrap !important;
            align-items: center !important;
            padding: 0.75rem !important;
        }

        .producto-cell {
            flex: 0 0 30% !important;
        }

        .cantidad-cell {
            flex: 0 0 15% !important;
            text-align: center !important;
        }

        .precio-cell {
            flex: 0 0 20% !important;
            text-align: right !important;
        }

        .action-buttons {
            flex: 0 0 35% !important;
            border-top: none !important;
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        .btn-group {
            display: flex;
            gap: 0.25rem;
        }

        .btn-group .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    }

    /* Ajuste del botón enviar a cocina en móviles */
    @media (max-width: 576px) {
        .btn-success {
            width: 100%;
            margin-top: 1rem;
            padding: 0.75rem;
        }
    }

    /* Estilos para nav pills */
    .nav-container {
        position: relative;
        margin: 0 -1rem;
        padding: 0 1rem;
    }

    .nav-pills {
        padding: 0.5rem 0;
        margin-bottom: 0;
    }

    .nav-pills .nav-link {
        white-space: nowrap;
        margin-right: 0.5rem;
        border-radius: 1rem;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Estilos para lista de productos */
    .list-group-item {
        padding: 0.75rem;
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .btn-circle {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 576px) {
        .list-group-item {
            padding: 0.5rem;
        }
        
        .list-group-item h6 {
            font-size: 0.9rem;
        }
        
        .list-group-item small {
            font-size: 0.8rem;
        }
        
        .btn-sm {
            width: 28px;
            height: 28px;
        }
    }

    /* Estilos mejorados para navegación y productos */
    .nav-container {
        background: #f8f9fa;
        border-radius: 1rem;
        padding: 0.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(33, 37, 41, 0.1);
    }

    .nav-pills {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .nav-pills .nav-link {
        color: #6c757d;
        padding: 0.5rem 1.2rem;
        margin: 0 0.25rem;
        border-radius: 2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
    }

    /* Estilos para items de producto */
    .product-item {
        background: white;
        padding: 1rem;
        border: 1px solid rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .product-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    .add-btn {
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        transform: scale(1.1);
    }

    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }

    /* Mejoras responsive */
    @media (min-width: 768px) {
        .nav-pills {
            justify-content: center;
        }
        
        .product-item {
            height: 100%;
        }
    }

    @media (max-width: 767.98px) {
        .nav-container {
            margin: 0 -1rem;
            border-radius: 0;
        }
        
        .product-item {
            margin-bottom: 0.5rem;
        }
    }

    /* Estilos para el encabezado de productos */
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .card-title {
        color: #2c3e50;
        font-weight: 500;
    }

    .text-muted.small {
        background-color: #e9ecef;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .text-muted.small i {
        font-size: 0.75rem;
    }

    @media (max-width: 576px) {
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .text-muted.small {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    /* Correcciones para los iconos y botones */
    .btn i.fas {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 1em !important;
        height: 1em !important;
        font-size: inherit !important;
    }

    .btn-circle,
    .add-btn,
    .btn-action {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
    }

    .btn i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 1em !important;
        height: 1em !important;
        vertical-align: middle !important;
    }

    /* Ajustes específicos para botones circulares */
    .rounded-circle.btn-sm {
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
    }

    /* Corrección de alineación en los badge */
    .badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 24px !important;
        height: 24px !important;
    }

    /* Ajustes responsive para iconos */
    @media (max-width: 576px) {
        .rounded-circle.btn-sm {
            width: 28px !important;
            height: 28px !important;
        }

        .btn i {
            font-size: 0.875rem !important;
        }

        .badge {
            min-width: 20px !important;
            height: 20px !important;
            font-size: 0.75rem !important;
        }
    }

    /* Ajuste de espaciado para botones con texto e icono */
    .btn-warning i,
    .btn-danger i {
        margin-right: 0.25rem !important;
    }

    @media (max-width: 575.98px) {
        .btn-warning i,
        .btn-danger i {
            margin-right: 0 !important;
        }
    }

    /* Actualizar estilos existentes y agregar nuevos */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
    }

    .card-header {
        background-color: #212529;
        color: white;
        border-bottom: none;
    }

    .nav-pills .nav-link.active {
        background-color: #212529;
        box-shadow: 0 2px 4px rgba(33, 37, 41, 0.3);
    }

    .nav-pills .nav-link {
        color: #212529;
        padding: 0.5rem 1.2rem;
        margin: 0 0.25rem;
        border-radius: 2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link:hover {
        background-color: rgba(33, 37, 41, 0.1);
    }

    .nav-container {
        background: #f8f9fa;
        border-radius: 1rem;
        padding: 0.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(33, 37, 41, 0.1);
    }

    .product-item {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 0.5rem;
    }

    .product-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .btn-success {
        background-color: #198754;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(33, 37, 41, 0.05);
    }

    /* Actualizar colores de botones */
    .btn-primary {
        background-color: #212529;
        border-color: #212529;
    }

    .btn-primary:hover {
        background-color: #343a40;
        border-color: #343a40;
    }

    .btn-outline-primary {
        color: #212529;
        border-color: #212529;
    }

    .btn-outline-primary:hover {
        background-color: #212529;
        border-color: #212529;
        color: #fff;
    }

    .nav-pills .nav-link.active {
        background-color: #212529;
    }

    .nav-pills .nav-link:hover {
        background-color: rgba(33, 37, 41, 0.1);
    }

    .badge.bg-primary {
        background-color: #212529 !important;
    }

    .add-btn {
        background-color: #212529;
        border-color: #212529;
    }

    .add-btn:hover {
        background-color: #343a40;
        border-color: #343a40;
    }

    :root {
        --restaurant-primary: #2c3e50;    /* Azul oscuro principal */
        --restaurant-secondary: #34495e;   /* Azul oscuro secundario */
        --restaurant-accent: #3498db;      /* Azul claro para acentos */
        --restaurant-light: #ecf0f1;       /* Gris muy claro para fondos */
        --restaurant-dark: #1a252f;        /* Azul muy oscuro */
    }

    .card-header {
        background: var(--restaurant-primary) !important;
    }

    .nav-pills .nav-link.active {
        background-color: var(--restaurant-primary);
        box-shadow: 0 2px 4px rgba(44, 62, 80, 0.3);
    }

    .navbar {
        background: var(--restaurant-dark) !important;
    }

    .btn-primary {
        background-color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }

    .btn-primary:hover {
        background-color: var(--restaurant-secondary);
        border-color: var(--restaurant-secondary);
    }

    .badge.bg-primary {
        background-color: var(--restaurant-primary) !important;
    }

    .add-btn {
        background-color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }

    .add-btn:hover {
        background-color: var(--restaurant-secondary);
        border-color: var(--restaurant-secondary);
    }

    .product-item:hover {
        box-shadow: 0 5px 15px rgba(44, 62, 80, 0.2);
    }

    .nav-pills .nav-link:hover {
        background-color: var(--restaurant-light);
    }

    .btn-outline-primary {
        color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }

    .btn-outline-primary:hover {
        background-color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }

    .nav-pills .nav-link.active {
        background-color: var(--restaurant-primary);
        box-shadow: 0 2px 4px rgba(44, 62, 80, 0.3);
    }

    .badge.bg-success {
        background-color: var(--restaurant-accent) !important;
    }

    .mesa-card:hover {
        box-shadow: 0 8px 15px rgba(44, 62, 80, 0.2);
    }

    .product-item {
        border: none;
        box-shadow: 0 2px 4px rgba(44, 62, 80, 0.1);
    }
    </style>
</body>
</html>
