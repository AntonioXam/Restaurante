<?php
include '../sesion.php';
include '../conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Camarero</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
    <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Restaurante</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="gestionar_mesas.php">Gestionar Mesas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <section class="p-4 bg-light border rounded shadow-sm">
            <h2>Panel de Camarero</h2>
            <p>Seleccione una opción del menú para comenzar.</p>
        <h3>Funciones del Camarero</h3>
        <ul>
            <li><strong>Asignar Mesas:</strong> El camarero puede asignar mesas a los clientes que llegan al restaurante.</li>
            <li><strong>Asignar Productos a las Mesas:</strong> El camarero puede tomar los pedidos de los clientes y asignar los productos a las mesas correspondientes.</li>
            <li><strong>Enviar Pedido a Cocina:</strong> Una vez que el pedido está completo, el camarero puede enviarlo a la cocina para su preparación.</li>
        </ul>
        </section>
    </div>
    <!-- bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
