<?php
include '../sesion.php';
include '../conexion.php';

// Verificar permisos de camarero
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'camarero') {
    header('Location: ../index.php');
    exit;
}

// Sanitizar mesa_id
$mesa_id = filter_input(INPUT_GET, 'mesa_id', FILTER_VALIDATE_INT);
if (!$mesa_id) {
    header('Location: gestionar_mesas.php');
    exit;
}

// Gestión de productos para una mesa específica
// Verifica que haya una mesa seleccionada
// Permite añadir productos al pedido agrupados por categoría
// Incluye un modal para modificar cantidades

// Funciones principales:
// - Obtener productos por categoría
// - Procesar nuevos pedidos
// - Mostrar productos en pestañas por categoría
// - Gestionar cantidades y notas

// Variables principales:
// $mesa_id - ID de la mesa actual
// $categorias - Array asociativo con las categorías de productos
// $result - Resultado de la consulta de productos por categoría

/**
 * Obtiene los productos filtrados por categoría
 * @param mysqli $conexion - Conexión a la base de datos
 * @param string $categoria - Nombre de la categoría a filtrar
 * @return mysqli_result - Resultado de la consulta
 */
function obtener_productos_por_categoria($conexion, $categoria) {
    $categoria = mysqli_real_escape_string($conexion, $categoria);
    $query = "SELECT id, nombre, precio, descripcion 
              FROM productos 
              WHERE categoria = ? AND activo = 1 
              ORDER BY nombre";
    
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $categoria);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// Procesar nuevo pedido con prepared statements
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['producto_id'])) {
        $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
        $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
        $notas = filter_input(INPUT_POST, 'notas', FILTER_SANITIZE_STRING);

        if ($producto_id && $cantidad > 0) {
            $stmt = mysqli_prepare($conexion, 
                "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, notas) 
                 SELECT p.id, ?, ?, ? 
                 FROM pedidos p 
                 WHERE p.mesa_id = ? AND p.estado = 'pendiente'");
            
            mysqli_stmt_bind_param($stmt, "iisi", $producto_id, $cantidad, $notas, $mesa_id);
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: gestionar_pedido.php?mesa_id=$mesa_id&status=success");
            } else {
                header("Location: gestionar_pedido.php?mesa_id=$mesa_id&status=error");
            }
            exit;
        }
    }
}

// Categorías de productos  y consulta de productos por categoría 
$categorias = [
    'pizzas' => 'Pizzas',
    'ensalada' => 'Ensaladas',
    'bebida' => 'Bebidas',
    'carne' => 'Carnes',
    'pasta' => 'Pasta',
    'pescado' => 'Pescado',
    'vino' => 'Vinos'
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="h4 mb-4">Gestionar Productos</h2>
                        
                        <ul class="nav nav-pills mb-3 hide-scrollbar" id="productTabs" role="tablist">
                            <?php foreach ($categorias as $key => $nombre): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $key === 'pizzas' ? 'active' : ''; ?>" 
                                       id="<?php echo $key; ?>-tab" 
                                       data-bs-toggle="pill" 
                                       href="#<?php echo $key; ?>" 
                                       role="tab">
                                        <?php echo $nombre; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="tab-content" id="productTabContent">
                            <?php foreach ($categorias as $key => $nombre): 
                                $result = obtener_productos_por_categoria($conexion, $key);
                            ?>
                                <div class="tab-pane fade <?php echo $key === 'pizzas' ? 'show active' : ''; ?>" 
                                     id="<?php echo $key; ?>" 
                                     role="tabpanel">
                                    <form action="procesar_pedido.php" method="post" class="needs-validation" novalidate>
                                        <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <select class="form-select" name="producto_id" required>
                                                    <option value="">Seleccione un producto</option>
                                                    <?php while ($producto = mysqli_fetch_assoc($result)): ?>
                                                        <option value="<?php echo $producto['id']; ?>">
                                                            <?php echo $producto['nombre']; ?> - 
                                                            <?php echo number_format($producto['precio'], 2); ?>€
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="number" class="form-control" name="cantidad" 
                                                       min="1" value="1" required>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    Añadir
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modificarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Cantidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="modificar">
                        <input type="hidden" name="detalle_id" id="mod_detalle_id">
                        <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                        <div class="form-group">
                            <label>Nueva cantidad:</label>
                            <input type="number" name="cantidad" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .nav-pills {
        border-radius: 0.5rem;
        padding: 0.5rem;
        background: #f8f9fa;
    }
    .nav-pills .nav-link {
        border-radius: 0.25rem;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        white-space: nowrap;
        font-size: 0.9rem;
    }
    .card {
        border-radius: 1rem;
        border: none;
    }
    @media (max-width: 768px) {
        .nav-pills .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
        .card {
            border-radius: 0;
        }
        .container-fluid {
            padding: 0;
        }
        .card-body {
            padding: 1rem;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    function modificarCantidad(detalle_id, cantidad_actual) {
        document.getElementById('mod_detalle_id').value = detalle_id;
        document.querySelector('#modificarModal input[name="cantidad"]').value = cantidad_actual;
        new bootstrap.Modal(document.getElementById('modificarModal')).show();
    }
    </script>
</body>
</html>

<?php
mysqli_close($conexion);
?>
