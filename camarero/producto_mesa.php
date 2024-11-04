<?php
// Añadir producto a una mesa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['añadir_producto'])) {
    $mesa_id = $_POST['mesa_id'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $notas = $_POST['notas'];

    $sql = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, notas) VALUES ('$mesa_id', '$producto_id', '$cantidad', '$notas')";
    $conn->query($sql);
    header("Location: mesas.php");
}

// Formulario para añadir producto
?>
<form method="post" action="">
    Mesa ID: <input type="text" name="mesa_id"><br>
    Producto ID: <input type="text" name="producto_id"><br>
    Cantidad: <input type="text" name="cantidad"><br>
    Notas: <input type="text" name="notas"><br>
    <input type="submit" name="añadir_producto" value="Añadir Producto">
</form>