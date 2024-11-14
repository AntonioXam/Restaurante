<?php
include '../sesion.php';
include '../conexion.php';

// Obtener el ID del producto a editar
$id = $_GET['id'];

// Obtener los datos del producto
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Validar que el precio y stock son números positivos
    if ($precio < 0 || $stock < 0) {
        echo "El precio y el stock deben ser números positivos.";
        exit;
    }

    // Actualizar los datos del producto
    $sql = "UPDATE productos SET nombre = ?, categoria = ?, precio = ?, stock = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdii", $nombre, $categoria, $precio, $stock, $id);

    if ($stmt->execute()) {
        header("Location: listar_productos.php");
    } else {
        echo "Error al actualizar el producto: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media (max-width: 768px) {
            .container { 
                padding: 10px; 
            }
            .btn-block {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        .form-card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            background: white;
            transition: all 0.3s ease;
        }
        .form-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transform: translateY(-3px);
        }
        /* ...existing styles... */
    </style>
</head>
<body class="bg-light">
    <div class="container mt-3">
        <header class="bg-primary text-white text-center py-3">
            <h1 class="h3">Editar Producto</h1>
        </header>
        <div class="row">
            <div class="col-12">
                <a href="listar_productos.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </div>
            <div class="col-12 col-md-6 offset-md-3">
                <div class="form-card">
                    <form action="editar_producto.php?id=<?php echo $id; ?>" method="post" class="needs-validation" novalidate>
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto:</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                            <div class="invalid-feedback">Por favor, ingrese el nombre del producto.</div>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría:</label>
                            <input type="text" class="form-control" name="categoria" id="categoria" value="<?php echo htmlspecialchars($producto['categoria']); ?>" required>
                            <div class="invalid-feedback">Por favor, ingrese la categoría.</div>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio (€):</label>
                            <input type="number" step="0.01" class="form-control" name="precio" id="precio" value="<?php echo number_format($producto['precio'], 2); ?>" required>
                            <div class="invalid-feedback">Por favor, ingrese el precio.</div>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock:</label>
                            <input type="number" class="form-control" name="stock" id="stock" value="<?php echo intval($producto['stock']); ?>" required>
                            <div class="invalid-feedback">Por favor, ingrese el stock.</div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Actualizar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de validación -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
<?php
// cerrar la conexion
$conexion->close();
?>