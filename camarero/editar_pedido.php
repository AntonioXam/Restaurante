<?php
include '../sesion.php';
include '../conexion.php';

// Obtener el ID del detalle del pedido
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $notas = $_POST['notas'];

    // Actualizar detalle del pedido
    $query = "UPDATE detalle_pedidos SET producto_id = '$producto_id', cantidad = '$cantidad', notas = '$notas' WHERE id = $id";
    mysqli_query($conexion, $query);

    // Actualizar total del pedido
    $query_total = "UPDATE pedidos SET total = (SELECT SUM(p.cantidad * pr.precio) FROM detalle_pedidos p JOIN productos pr ON p.producto_id = pr.id WHERE p.pedido_id = (SELECT pedido_id FROM detalle_pedidos WHERE id = $id)) WHERE id = (SELECT pedido_id FROM detalle_pedidos WHERE id = $id)";
    mysqli_query($conexion, $query_total);

    // Redirigir a la página de pedidos
    header("Location: pedidos.php?mesa_id=" . $_GET['mesa_id']);
}

// Obtener detalle del pedido
$query = "SELECT * FROM detalle_pedidos WHERE id = $id";
$result = mysqli_query($conexion, $query);
$detalle = mysqli_fetch_assoc($result);

// Obtener productos
$query_productos = "SELECT * FROM productos";
$result_productos = mysqli_query($conexion, $query_productos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Editar Pedido</h1>
    </header>
    <nav>
        <ul>
            <li><a href="../logout.php">Cerrar Sesión</a></li>
            <li><button onclick="history.back()">Volver</button></li>
        </ul>
    </nav>
    <section>
        <form method="POST">
            <label for="producto_id">Producto:</label>
            <select name="producto_id" id="producto_id">
                <?php while ($producto = mysqli_fetch_assoc($result_productos)) { ?>
                <option value="<?php echo $producto['id']; ?>" <?php if ($detalle['producto_id'] == $producto['id']) echo 'selected'; ?>><?php echo $producto['nombre']; ?></option>
                <?php } ?>
            </select>
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" value="<?php echo $detalle['cantidad']; ?>" required>
            <label for="notas">Notas:</label>
            <input type="text" name="notas" id="notas" value="<?php echo $detalle['notas']; ?>">
            <button type="submit">Actualizar</button>
        </form>
    </section>
</body>
</html>
