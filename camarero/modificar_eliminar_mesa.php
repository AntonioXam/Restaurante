<?php
// Modificar mesa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modificar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];
    $numero_mesa = $_POST['numero_mesa'];
    $estado = $_POST['estado'];
    $comensales = $_POST['comensales'];

    $sql = "UPDATE mesas SET numero_mesa='$numero_mesa', estado='$estado', comensales='$comensales' WHERE id='$mesa_id' AND camarero_id='$camarero_id'";
    $conn->query($sql);
    header("Location: mesas.php");
}

// Eliminar mesa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];

    $sql = "DELETE FROM mesas WHERE id='$mesa_id' AND camarero_id='$camarero_id'";
    $conn->query($sql);
    header("Location: mesas.php");
}

// Formularios para modificar y eliminar mesa
?>
<form method="post" action="">
    Mesa ID: <input type="text" name="mesa_id"><br>
    Número de mesa: <input type="text" name="numero_mesa"><br>
    Estado: <input type="text" name="estado"><br>
    Comensales: <input type="text" name="comensales"><br>
    <input type="submit" name="modificar_mesa" value="Modificar Mesa">
</form>

<form method="post" action="">
    Mesa ID: <input type="text" name="mesa_id"><br>
    <input type="submit" name="eliminar_mesa" value="Eliminar Mesa">
</form>