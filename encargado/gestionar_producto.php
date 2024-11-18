<?php

// Incluir archivos de sesión y conexión
include 'sesion_encargado.php';
include '../conexion.php';

// Obtener ID del producto
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Manejar la solicitud POST para actualizar el producto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
    $stock = mysqli_real_escape_string($conexion, $_POST['stock']);

    $sql = "UPDATE productos SET nombre='$nombre', categoria='$categoria', precio='$precio', stock='$stock' WHERE id=$id";

    if (mysqli_query($conexion, $sql)) {
        header("Location: listar_productos.php?mensaje=Producto modificado exitosamente");
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}

// Obtener datos del producto
$producto = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM productos WHERE id=$id"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metadatos y título -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Producto</title>
    <!-- CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Cabecera -->
    <header class="bg-secondary text-white py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Modificar Producto</h1>
            <a href="listar_productos.php" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </header>

    <!-- Formulario de modificación -->
    <div class="container mt-4">
        <a href="listar_productos.php" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <form method="POST" action="modificar_producto.php?id=<?php echo $id; ?>">
            <!-- Campo Nombre -->
            <div class="form-group">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>
            </div>
            <!-- Campo Categoría -->
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" class="form-control" id="categoria" name="categoria" value="<?php echo $producto['categoria']; ?>" required>
            </div>
            <!-- Campo Precio -->
            <div class="form-group">
                <label for="precio">Precio (€):</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required>
            </div>
            <!-- Campo Stock -->
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>
            </div>
            <!-- Botón de envío -->
            <button type="submit" class="btn btn-info">Modificar Producto</button>
        </form>
    </div>

    <!-- Scripts de JavaScript -->
    <!-- ...existing scripts... -->
</body>
</html>
<?php
// Cerrar conexión
$conexion->close();
?>