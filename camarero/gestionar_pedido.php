<?php
session_start();
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

$query = "SELECT * FROM productos";
$result = mysqli_query($conexion, $query);

$mesas_query = "SELECT * FROM mesas WHERE estado = 'activa'";
$mesas_result = mysqli_query($conexion, $mesas_query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Pedido</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Gestionar Pedido</h1>
    </header>
    <nav>
        <ul>
            <li><a href="gestionar_mesas.php">Volver</a></li>
        </ul>
    </nav>
    <section>
        <h2>Seleccionar Mesa</h2>
        <form action="" method="get">
            <label for="mesa_id">Mesa:</label>
            <select id="mesa_id" name="mesa_id" onchange="this.form.submit()">
                <option value="">Seleccione una mesa</option>
                <?php while ($mesa = mysqli_fetch_assoc($mesas_result)) { ?>
                <option value="<?php echo $mesa['id']; ?>" <?php if ($mesa['id'] == $mesa_id) echo 'selected'; ?>>
                    Mesa <?php echo $mesa['numero_mesa']; ?>
                </option>
                <?php } ?>
            </select>
        </form>
    </section>
    <?php if ($mesa_id) { ?>
    <section>
        <h2>Añadir Producto</h2>
        <form action="" method="post">
            <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
            <label for="producto_id">Producto:</label>
            <select id="producto_id" name="producto_id">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                <?php } ?>
            </select>
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" required>
            <label for="notas">Notas:</label>
            <input type="text" id="notas" name="notas">
            <button type="submit">Añadir</button>
        </form>
    </section>
    <?php } ?>
</body>
</html>
