<?php
include '../sesion.php';
include '../conexion.php';

// Obtener todos los productos ordenados por nombre
$sql = "SELECT * FROM productos ORDER BY nombre ASC";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ...existing styles... */
        .table-responsive {
            margin-top: 20px;
        }
        /* Estilos adicionales para mobile */
        @media (max-width: 768px) {
            .table-responsive { font-size: 0.85rem; }
            .container { padding: 10px; }
            .btn-group-sm > .btn, .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
            .table td, .table th { padding: 0.5rem; }
            .img-thumbnail { width: 40px; height: 40px; }
            .btn-back { width: 100%; margin-bottom: 20px; }
            h2 { font-size: 1.5rem; margin-bottom: 20px; }
            .btn-block {
                width: 100%;
                margin-bottom: 10px;
            }
            .actions-column { min-width: 120px; }
            .back-button { margin: 10px 0; }
            .table-desktop {
                display: none;
            }
            .card-mobile {
                margin-bottom: 0.75rem;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                padding: 20px;
            }
            .card-mobile .img-thumbnail {
                width: 50px;
                height: 50px;
                object-fit: cover;
                border-radius: 50%;
            }
            .card-mobile .card-body {
                padding: 0.75rem;
            }
            .card-mobile .info-row {
                font-size: 0.85rem;
                color: #666;
                margin: 5px 0;
                padding: 0.5rem 0;
                border-bottom: 1px solid #eee;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .card-mobile .info-row:last-child {
                border-bottom: none;
                margin-bottom: 0.5rem;
            }
            .actions {
                margin-top: 1rem;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }
            .actions button, .actions a {
                width: 100%;
                padding: 0.5rem;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
        @media (min-width: 769px) {
            .cards-mobile {
                display: none;
            }
        }
        .list-card {
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: white;
            padding: 20px;
        }
        .list-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .table {
            margin-bottom: 0;
        }
    </style>
</head>
<body class="bg-light">
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Listado de Productos</h1>
    </header>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-12">
                <a href="index.php" class="btn btn-secondary btn-block mb-4">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
                <a href="agregar_producto.php" class="btn btn-success btn-block">
                    <i class="fas fa-plus"></i> Agregar Producto
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive table-desktop">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio (€)</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($producto = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                                <td><?php echo number_format($producto['precio'], 2); ?></td>
                                <td><?php echo intval($producto['stock']); ?></td>
                                <td>
                                    <a href="editar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#confirmarEliminarModal"
                                        data-id="<?php echo $producto['id']; ?>"
                                        data-nombre="<?php echo $producto['nombre']; ?>">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if($resultado->num_rows == 0) { ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay productos registrados.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="cards-mobile">
                    <?php 
                    // Reset the result pointer
                    $resultado->data_seek(0);
                    while ($producto = $resultado->fetch_assoc()) { ?>
                        <div class="card card-mobile">
                            <div class="card-body">
                                <div class="info-row">
                                    <span class="info-label">Nombre:</span>
                                    <span class="info-content"><?php echo htmlspecialchars($producto['nombre']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Categoría:</span>
                                    <span class="info-content"><?php echo htmlspecialchars($producto['categoria']); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Precio:</span>
                                    <span class="info-content"><?php echo number_format($producto['precio'], 2); ?> €</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Stock:</span>
                                    <span class="info-content"><?php echo intval($producto['stock']); ?></span>
                                </div>

                                <div class="actions">
                                    <a href="editar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-danger"
                                        data-toggle="modal"
                                        data-target="#confirmarEliminarModal"
                                        data-id="<?php echo $producto['id']; ?>"
                                        data-nombre="<?php echo $producto['nombre']; ?>">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Eliminar -->
    <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" role="dialog" aria-labelledby="confirmarEliminarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="gestionar_producto.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmarEliminarLabel">Eliminar Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Mensaje de confirmación -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="accion" value="eliminar">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    // Script para manejar los datos del modal
    $('#confirmarEliminarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productoId = button.data('id');
        var nombre = button.data('nombre');
        var modal = $(this);
        modal.find('.modal-body').text('¿Está seguro que desea eliminar el producto "' + nombre + '"?');
        modal.find('input[name="id"]').val(productoId);
    });
    </script>
</body>
</html>
<?php
// Cerrar la conexión
$conexion->close();
?>