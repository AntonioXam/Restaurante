<?php
include '../sesion.php';
include '../conexion.php';

// Funciones
function obtener_mesas_activas($conexion) {
    return mysqli_query($conexion, "SELECT * FROM mesas WHERE estado = 'activa'");
}

// Lógica para activar mesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];
    $comensales = $_POST['comensales'];

    mysqli_query($conexion, "UPDATE mesas SET estado = 'activa', comensales = $comensales WHERE id = $mesa_id");
    mysqli_query($conexion, "INSERT INTO pedidos (mesa_id, estado, total) VALUES ($mesa_id, 'pendiente', 0.00)");

    header("Location: gestionar_pedido.php?mesa_id=$mesa_id");
    exit();
}

$mesa_id = isset($_GET['mesa_id']) ? $_GET['mesa_id'] : null;
$mesas_inactivas_result = mysqli_query($conexion, "SELECT * FROM mesas WHERE estado = 'inactiva'");
$mesas_activas_result = obtener_mesas_activas($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Mesas - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Gestionar Mesas</h1>
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
    <div class="container-fluid py-3">
        <section class="p-4 bg-light border rounded shadow-sm">
            <h2>Seleccionar Mesa Activa</h2>
            <form action="" method="get" class="form-inline">
                <label for="mesa_activa_id" class="mr-2">Mesa:</label>
                <select id="mesa_activa_id" name="mesa_id" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Seleccione una mesa</option>
                    <?php 
                    $mesas_activas = obtener_mesas_activas($conexion);
                    while ($mesa = mysqli_fetch_assoc($mesas_activas)) { ?>
                    <option value="<?php echo $mesa['id']; ?>">Mesa <?php echo $mesa['numero_mesa']; ?></option>
                    <?php } ?>
                </select>
            </form>
        </section>
        <section class="mt-4 p-4 bg-light border rounded shadow-sm">
            <h2>Activar Mesa Nueva</h2>
            <form action="" method="get" class="form-inline">
                <label for="mesa_id" class="mr-2">Mesa:</label>
                <select id="mesa_id" name="mesa_id" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Seleccione una mesa</option>
                    <?php while ($mesa = mysqli_fetch_assoc($mesas_inactivas_result)) { ?>
                    <option value="<?php echo $mesa['id']; ?>" <?php if ($mesa['id'] == $mesa_id) echo 'selected'; ?>>
                        Mesa <?php echo $mesa['numero_mesa']; ?>
                    </option>
                    <?php } ?>
                </select>
            </form>
        </section>
        <?php if ($mesa_id) { ?>
        <section class="mt-4 p-4 bg-light border rounded shadow-sm">
            <h2>Activar Mesa</h2>
            <form action="" method="post" class="form">
                <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                <div class="form-group">
                    <label for="comensales">Número de Comensales:</label>
                    <input type="number" class="form-control" id="comensales" name="comensales" required>
                </div>
                <button type="submit" name="activar_mesa" class="btn btn-primary">Activar Mesa</button>
            </form>
        </section>
        <?php } ?>
        <section class="mt-4 p-4 bg-light border rounded shadow-sm">
            <h2>Mesas Activas</h2>
            <ul class="list-group">
                <?php 
                $mesas_activas = obtener_mesas_activas($conexion);
                while ($mesa = mysqli_fetch_assoc($mesas_activas)) { ?>
                <li class="list-group-item">
                    Mesa <?php echo $mesa['numero_mesa']; ?> - Comensales: <?php echo $mesa['comensales']; ?>
                    <a href="gestionar_pedido.php?mesa_id=<?php echo $mesa['id']; ?>" class="btn btn-primary btn-sm float-right">Gestionar</a>
                </li>
                <?php } ?>
            </ul>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
