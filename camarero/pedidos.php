<?php
include '../sesion.php';
include '../conexion.php';

// Obtener el ID de la mesa
$mesa_id = $_GET['mesa_id'];

// Obtener productos
$query_productos = "SELECT * FROM productos";
$result_productos = mysqli_query($conexion, $query_productos);

// Obtener detalles de la mesa
$query_mesa = "SELECT * FROM mesas WHERE id = $mesa_id";
$result_mesa = mysqli_query($conexion, $query_mesa);
$mesa = mysqli_fetch_assoc($result_mesa);

// Obtener pedidos actuales
$query_pedidos = "SELECT p.id, p.estado, p.total, dp.producto_id, dp.cantidad, dp.notas, pr.nombre AS producto_nombre
                  FROM pedidos p
                  JOIN detalle_pedidos dp ON p.id = dp.pedido_id
                  JOIN productos pr ON dp.producto_id = pr.id
                  WHERE p.mesa_id = $mesa_id AND p.estado = 'pendiente'";
$result_pedidos = mysqli_query($conexion, $query_pedidos);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $notas = $_POST['notas'];

    // Verificar si ya existe un pedido pendiente para la mesa
    $query_pedido_existente = "SELECT id FROM pedidos WHERE mesa_id = $mesa_id AND estado = 'pendiente'";
    $result_pedido_existente = mysqli_query($conexion, $query_pedido_existente);
    if (mysqli_num_rows($result_pedido_existente) > 0) {
        $pedido = mysqli_fetch_assoc($result_pedido_existente);
        $pedido_id = $pedido['id'];
    } else {
        // Insertar nuevo pedido
        $query_pedido = "INSERT INTO pedidos (mesa_id, camarero_id, estado, total) VALUES ($mesa_id, {$_SESSION['usuario_id']}, 'pendiente', 0)";
        mysqli_query($conexion, $query_pedido);
        $pedido_id = mysqli_insert_id($conexion);
    }

    // Insertar detalle del pedido
    $query_detalle = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, notas) VALUES ($pedido_id, $producto_id, $cantidad, '$notas')";
    mysqli_query($conexion, $query_detalle);

    // Actualizar total del pedido
    $query_total = "UPDATE pedidos SET total = (SELECT SUM(p.cantidad * pr.precio) FROM detalle_pedidos p JOIN productos pr ON p.producto_id = pr.id WHERE p.pedido_id = $pedido_id) WHERE id = $pedido_id";
    mysqli_query($conexion, $query_total);

    // Redirigir a la página de pedidos
    header("Location: pedidos.php?mesa_id=$mesa_id");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedido</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Gestión de Pedido para Mesa <?php echo $mesa['numero_mesa']; ?></h1>
    </header>
    <section>
        <h2>Agregar Producto</h2>
        <form method="POST">
            <label for="producto_id">Producto:</label>
            <select name="producto_id" id="producto_id">
                <?php while ($producto = mysqli_fetch_assoc($result_productos)) { ?>
                <option value="<?php echo $producto['id']; ?>"><?php echo $producto['nombre']; ?></option>
                <?php } ?>
            </select>
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" required>
            <label for="notas">Notas:</label>
            <input type="text" name="notas" id="notas">
            <button type="submit">Agregar</button>
        </form>
        <h2>Pedidos Actuales</h2>
        <table>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Notas</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
            <?php while ($pedido = mysqli_fetch_assoc($result_pedidos)) { ?>
            <tr>
                <td><?php echo $pedido['producto_nombre']; ?></td>
                <td><?php echo $pedido['cantidad']; ?></td>
                <td><?php echo $pedido['notas']; ?></td>
                <td><?php echo $pedido['total']; ?></td>
                <td>
                    <a href="editar_pedido.php?id=<?php echo $pedido['id']; ?>&mesa_id=<?php echo $mesa_id; ?>">Editar</a>
                    <a href="eliminar_pedido.php?id=<?php echo $pedido['id']; ?>&mesa_id=<?php echo $mesa_id; ?>">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </section>
    <footer>
        <button onclick="location.href='../logout.php'">Cerrar Sesión</button>
    </footer>
</body>
</html>