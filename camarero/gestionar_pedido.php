<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? $_GET['mesa_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'procesar_pedido.php';
}

$pizzas_query = "SELECT * FROM productos WHERE categoria = 'Comida' AND nombre LIKE '%Pizza%'";
$pizzas_result = mysqli_query($conexion, $pizzas_query);

$ensaladas_query = "SELECT * FROM productos WHERE categoria = 'Comida' AND nombre LIKE '%Ensalada%'";
$ensaladas_result = mysqli_query($conexion, $ensaladas_query);

$bebidas_query = "SELECT * FROM productos WHERE categoria = 'Bebida'";
$bebidas_result = mysqli_query($conexion, $bebidas_query);

$mesas_activas_query = "SELECT * FROM mesas WHERE estado = 'activa'";
$mesas_activas_result = mysqli_query($conexion, $mesas_activas_query);

$mesa = null;
$detalle_pedidos_result = null;
if ($mesa_id) {
    $mesa_query = "SELECT * FROM mesas WHERE id = $mesa_id";
    $mesa_result = mysqli_query($conexion, $mesa_query);
    $mesa = mysqli_fetch_assoc($mesa_result);

    $detalle_pedidos_query = "SELECT dp.cantidad, p.nombre, p.precio FROM detalle_pedidos dp JOIN productos p ON dp.producto_id = p.id WHERE dp.pedido_id = (SELECT id FROM pedidos WHERE mesa_id = $mesa_id AND estado = 'pendiente')";
    $detalle_pedidos_result = mysqli_query($conexion, $detalle_pedidos_query);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pedido</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Gestionar Pedido <?php echo $mesa ? '- Mesa ' . $mesa['numero_mesa'] : ''; ?></h1>
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
                        <a class="nav-link" href="index.php">Volver</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <?php if (!$mesa_id) { ?>
        <?php include 'seleccionar_mesa.php'; ?>
        <?php } else { ?>
        <?php include 'seleccionar_producto.php'; ?>
        <?php include 'productos_anadidos.php'; ?>
        <form action="enviar_a_cocina.php" method="post" class="mt-4">
            <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
            <button type="submit" class="btn btn-success">Enviar a Cocina</button>
        </form>
        <?php } ?>
    </div>
    <!-- bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
