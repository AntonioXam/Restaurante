<?php
include '../sesion.php';
include '../conexion.php';

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

    $sql = "INSERT INTO productos (nombre, categoria, precio, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdi", $nombre, $categoria, $precio, $stock);

    if ($stmt->execute()) {
        header("Location: listar_productos.php");
    } else {
        echo "Error al agregar el producto: " . $conexion->error;
    }
    $stmt->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* ...existing styles... */
        .form-card {
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .form-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        @media (max-width: 768px) {
            .container { 
                padding: 10px; 
            }
            .btn-block {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        /* ...existing styles... */
    </style>
</head>
<body class="bg-light">
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Agregar Nuevo Producto</h1>
    </header>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <a href="listar_productos.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </div>
            <div class="col-12 col-md-6 offset-md-3">
                <div class="form-card">
                    <form action="agregar_producto.php" method="post" class="needs-validation" novalidate>
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto:</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required>
                            <div class="invalid-feedback">Por favor, ingrese el nombre del producto.</div>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría:</label>
                            <input type="text" class="form-control" name="categoria" id="categoria" required>
                            <div class="invalid-feedback">Por favor, ingrese la categoría.</div>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio (€):</label>
                            <input type="number" step="0.01" class="form-control" name="precio" id="precio" required>
                            <div class="invalid-feedback">Por favor, ingrese el precio.</div>
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock:</label>
                            <input type="number" class="form-control" name="stock" id="stock" required>
                            <div class="invalid-feedback">Por favor, ingrese el stock.</div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Agregar Producto</button>
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