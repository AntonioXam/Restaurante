<?php
include '../sesion.php';
include '../conexion.php';

$mesa_id = isset($_GET['mesa_id']) ? $_GET['mesa_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mesa_id = $_POST['mesa_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $notas = $_POST['notas'];

    $query = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, notas) VALUES ((SELECT id FROM pedidos WHERE mesa_id = $mesa_id AND estado = 'pendiente'), $producto_id, $cantidad, '$notas')";
    mysqli_query($conexion, $query);
}

$pizzas_query = "SELECT * FROM productos WHERE categoria = 'Comida' AND nombre LIKE '%Pizza%'";
$pizzas_result = mysqli_query($conexion, $pizzas_query);

$ensaladas_query = "SELECT * FROM productos WHERE categoria = 'Comida' AND nombre LIKE '%Ensalada%'";
$ensaladas_result = mysqli_query($conexion, $ensaladas_query);

$bebidas_query = "SELECT * FROM productos WHERE categoria = 'Bebida'";
$bebidas_result = mysqli_query($conexion, $bebidas_query);
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
        <h1>Gestionar Pedido</h1>
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
        <section class="p-4 bg-light border rounded shadow-sm">
            <h2>Seleccionar Producto</h2>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pizzas-tab" data-toggle="tab" href="#pizzas" role="tab" aria-controls="pizzas" aria-selected="true">Pizzas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="ensaladas-tab" data-toggle="tab" href="#ensaladas" role="tab" aria-controls="ensaladas" aria-selected="false">Ensaladas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bebidas-tab" data-toggle="tab" href="#bebidas" role="tab" aria-controls="bebidas" aria-selected="false">Bebidas</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="pizzas" role="tabpanel" aria-labelledby="pizzas-tab">
                    <form action="" method="post" class="form mt-3">
                        <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                        <div class="form-group">
                            <label for="producto_id">Producto:</label>
                            <select id="producto_id" name="producto_id" class="form-control">
                                <?php while ($row = mysqli_fetch_assoc($pizzas_result)) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <div class="form-group">
                            <label for="notas">Notas:</label>
                            <textarea class="form-control" id="notas" name="notas"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="ensaladas" role="tabpanel" aria-labelledby="ensaladas-tab">
                    <form action="" method="post" class="form mt-3">
                        <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                        <div class="form-group">
                            <label for="producto_id">Producto:</label>
                            <select id="producto_id" name="producto_id" class="form-control">
                                <?php while ($row = mysqli_fetch_assoc($ensaladas_result)) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <div class="form-group">
                            <label for="notas">Notas:</label>
                            <textarea class="form-control" id="notas" name="notas"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </form>
                </div>
                <div class="tab-pane fade" id="bebidas" role="tabpanel" aria-labelledby="bebidas-tab">
                    <form action="" method="post" class="form mt-3">
                        <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                        <div class="form-group">
                            <label for="producto_id">Producto:</label>
                            <select id="producto_id" name="producto_id" class="form-control">
                                <?php while ($row = mysqli_fetch_assoc($bebidas_result)) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <div class="form-group">
                            <label for="notas">Notas:</label>
                            <textarea class="form-control" id="notas" name="notas"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <!-- bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
