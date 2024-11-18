<?php
include 'sesion_encargado.php';
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $precio = mysqli_real_escape_string($conexion, $_POST['precio']);
    $stock = mysqli_real_escape_string($conexion, $_POST['stock']);

    $sql = "INSERT INTO productos (nombre, categoria, precio, stock) VALUES ('$nombre', '$categoria', '$precio', '$stock')";

    if (mysqli_query($conexion, $sql)) {
        header("Location: listar_productos.php?mensaje=Producto añadido exitosamente");
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Producto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-secondary text-white py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Añadir Nuevo Producto</h1>
            <a href="listar_productos.php" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </header>

    <div class="container mt-4">
        <form method="POST" action="agregar_producto.php">
            <div class="form-group">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <input type="text" class="form-control" id="categoria" name="categoria" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio (€):</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <button type="submit" class="btn btn-primary">Añadir Producto</button>
        </form>
    </div>

    <!-- ...existing scripts... -->
</body>
</html>
<?php
$conexion->close();
?>