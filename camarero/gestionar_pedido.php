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
                break;

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
                mysqli_query($conexion, "UPDATE pedidos SET estado = 'enviado' 
                                       WHERE mesa_id = $mesa_id AND estado = 'pendiente'");
                break;
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
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Restaurante</a>
            <div class="d-flex align-items-center">
                <?php if(isset($mesa) && $mesa): ?>
                    <span class="navbar-text me-3">
                        Mesa: <?php echo htmlspecialchars($mesa_numero); ?>
                    </span>
                <?php endif; ?>
                <a href="index.php" class="btn btn-outline-light">
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
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Añadir Productos</h5>
                            
                            <!-- Pestañas de categorías -->
                            <ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
                                <?php
                                // Obtener categorías únicas de la base de datos
                                $query_categorias = "SELECT DISTINCT categoria FROM productos ORDER BY categoria";
                                $result_categorias = mysqli_query($conexion, $query_categorias);
                                $first = true;
                                while ($cat = mysqli_fetch_assoc($result_categorias)):
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $first ? 'active' : ''; ?>" 
                                       id="<?php echo $cat['categoria']; ?>-tab" 
                                       data-bs-toggle="tab" 
                                       href="#<?php echo $cat['categoria']; ?>" 
                                       role="tab">
                                        <?php echo ucfirst($cat['categoria']); ?>
                                    </a>
                                </li>
                                <?php 
                                $first = false;
                                endwhile; 
                                ?>
                            </ul>

                            <!-- Contenido de las pestañas -->
                            <div class="tab-content" id="productTabContent">
                                <?php 
                                // Reiniciar el puntero de categorías
                                mysqli_data_seek($result_categorias, 0);
                                $first = true;
                                while ($cat = mysqli_fetch_assoc($result_categorias)): 
                                ?>
                                <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" 
                                     id="<?php echo $cat['categoria']; ?>" 
                                     role="tabpanel">
                                    
                                    <div class="row">
                                        <?php
                                        $query = "SELECT * FROM productos WHERE categoria = '{$cat['categoria']}'";
                                        $productos = mysqli_query($conexion, $query);
                                        while ($producto = mysqli_fetch_assoc($productos)):
                                        ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                                    <p class="card-text"><?php echo number_format($producto['precio'], 2); ?>€</p>
                                                    <form action="" method="POST" class="add-product-form">
                                                        <input type="hidden" name="action" value="agregar">
                                                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                                                        <div class="form-group mb-2">
                                                            <label>Cantidad:</label>
                                                            <input type="number" name="cantidad" class="form-control" value="1" min="1" required>
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label>Notas:</label>
                                                            <textarea name="notas" class="form-control" rows="2"></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-sm">Añadir</button>
                                                    </form>
                                                </div>
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

            <!-- Productos Añadidos -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Productos en el Pedido</h5>
                            <?php if ($detalle_pedidos_result && mysqli_num_rows($detalle_pedidos_result) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unitario</th>
                                                <th>Subtotal</th>
                                                <th>Notas</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total = 0;
                                            while ($detalle = mysqli_fetch_assoc($detalle_pedidos_result)): 
                                                $subtotal = $detalle['cantidad'] * $detalle['precio'];
                                                $total += $subtotal;
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                                                <td><?php echo $detalle['cantidad']; ?></td>
                                                <td><?php echo number_format($detalle['precio'], 2); ?>€</td>
                                                <td><?php echo number_format($subtotal, 2); ?>€</td>
                                                <td><?php echo htmlspecialchars($detalle['notas']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" 
                                                            onclick="modificarCantidad(<?php echo $detalle['id']; ?>, <?php echo $detalle['cantidad']; ?>, '<?php echo htmlspecialchars($detalle['notas']); ?>')">
                                                        <i class="fas fa-edit"></i> Modificar
                                                    </button>
                                                    <form action="" method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="eliminar">
                                                        <input type="hidden" name="detalle_id" value="<?php echo $detalle['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                            <i class="fas fa-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                <td><strong><?php echo number_format($total, 2); ?>€</strong></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <form action="" method="POST" class="mt-3">
                                        <input type="hidden" name="action" value="enviar_cocina">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-utensils"></i> Enviar a Cocina
                                        </button>
                                    </form>
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

    <script>
    function modificarCantidad(detalle_id, cantidad, notas) {
        document.getElementById('mod_detalle_id').value = detalle_id;
        document.getElementById('mod_cantidad').value = cantidad;
        document.getElementById('mod_notas').value = notas;
        new bootstrap.Modal(document.getElementById('modificarModal')).show();
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
    </style>
</body>
</html>
