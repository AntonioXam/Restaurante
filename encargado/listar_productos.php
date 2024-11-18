<?php 

// Incluir archivos de sesión y conexión
include 'sesion_encargado.php';
include '../conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metadatos y título -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <!-- CSS de Bootstrap y Font Awesome -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <style>
        /* Estilos responsivos para tablas */
        @media (max-width: 576px) {
            .table thead {
                display: none;
            }
            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
            }
            .table tbody tr td {
                display: flex;
                justify-content: space-between;
                padding-left: 50%;
                position: relative;
            }
            .table tbody tr td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
        }
        /* ...existing styles... */
    </style>
</head>
<body>
    <!-- Contenedor principal -->
    <div class="container mt-5">
        <h1 class="mb-4">Listado de Productos</h1>
        <!-- Botones de acción -->
        <a href="agregar_producto.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Añadir Producto</a>
        <a href="index.php" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <!-- Formulario de filtro por categoría -->
        <form method="GET" action="">
            <div class="form-group">
                <label for="categoria">Filtrar por Categoría:</label>
                <select name="categoria" id="categoria" class="form-control" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <?php
                    // Obtener categorías distintas
                    $categorias = mysqli_query($conexion, "SELECT DISTINCT categoria FROM productos");
                    while($categoria = mysqli_fetch_assoc($categorias)) {
                        $selected = (isset($_GET['categoria']) && $_GET['categoria'] == $categoria['categoria']) ? 'selected' : '';
                        echo "<option value='{$categoria['categoria']}' {$selected}>{$categoria['categoria']}</option>";
                    }
                    ?>
                </select>
            </div>
        </form>
        <!-- Tabla de productos -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Manejar filtro de categoría
                    $categoriaFiltro = isset($_GET['categoria']) ? mysqli_real_escape_string($conexion, $_GET['categoria']) : '';
                    $sql = "SELECT * FROM productos";
                    if($categoriaFiltro) {
                        $sql .= " WHERE categoria = '$categoriaFiltro'";
                    }
                    $productos = mysqli_query($conexion, $sql);
                    while($producto = mysqli_fetch_assoc($productos)):
                    ?>
                    <tr>
                        <td data-label="ID"><?php echo $producto['id']; ?></td>
                        <td data-label="Nombre"><?php echo $producto['nombre']; ?></td>
                        <td data-label="Categoría"><?php echo $producto['categoria']; ?></td>
                        <td data-label="Precio"><?php echo $producto['precio']; ?></td>
                        <td data-label="Stock"><?php echo $producto['stock']; ?></td>
                        <td data-label="Acciones">
                            <!-- Botones de modificar y eliminar -->
                            <a href="modificar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i> Modificar
                            </a>
                            <a href="eliminar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este producto?');">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts de JavaScript -->
    <!-- ...existing scripts... -->
</body>
</html>
<?php
// Cerrar conexión
$conexion->close();
?>