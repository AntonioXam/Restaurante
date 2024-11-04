<?php
session_start();
if (!isset($_SESSION['camarero_id'])) {
    header("Location: login.php");
    exit();
}

$camarero_id = $_SESSION['camarero_id'];

// Mostrar mesas
$sql = "SELECT * FROM mesas WHERE camarero_id='$camarero_id'";
$result = $conn->query($sql);

echo "<h1>Mesas</h1>";
while ($row = $result->fetch_assoc()) {
    echo "Mesa " . $row['numero_mesa'] . " - Estado: " . $row['estado'] . "<br>";
}

// Crear mesa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_mesa'])) {
    $numero_mesa = $_POST['numero_mesa'];
    $estado = $_POST['estado'];
    $comensales = $_POST['comensales'];

    $sql = "INSERT INTO mesas (numero_mesa, estado, comensales, camarero_id) VALUES ('$numero_mesa', '$estado', '$comensales', '$camarero_id')";
    $conn->query($sql);
    header("Location: mesas.php");
}

// Formulario para crear mesa
?>
<form method="post" action="">
    Número de mesa: <input type="text" name="numero_mesa"><br>
    Estado: <input type="text" name="estado"><br>
    Comensales: <input type="text" name="comensales"><br>
    <input type="submit" name="crear_mesa" value="Crear Mesa">
</form>