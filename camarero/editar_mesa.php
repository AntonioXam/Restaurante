<?php
include '../sesion.php';
include '../conexion.php';

// Obtener el ID de la mesa
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $numero_mesa = $_POST['numero_mesa'];
    $comensales = $_POST['comensales'];
    $estado = $_POST['estado'];

    // Actualizar mesa
    $query = "UPDATE mesas SET numero_mesa = '$numero_mesa', comensales = '$comensales', estado = '$estado' WHERE id = $id";
    mysqli_query($conexion, $query);

    // Redirigir a la página de mesas
    header("Location: mesas.php");
}

// Obtener detalles de la mesa
$query = "SELECT * FROM mesas WHERE id = $id";
$result = mysqli_query($conexion, $query);
$mesa = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Editar Mesa</h1>
    </header>
    <nav>
        <ul>
            <li><a href="../logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <section>
        <form method="POST">
            <label for="numero_mesa">Número de Mesa:</label>
            <input type="text" name="numero_mesa" id="numero_mesa" value="<?php echo $mesa['numero_mesa']; ?>" required>
            <label for="comensales">Comensales:</label>
            <input type="number" name="comensales" id="comensales" value="<?php echo $mesa['comensales']; ?>" required>
            <label for="estado">Estado:</label>
            <select name="estado" id="estado">
                <option value="activa" <?php if ($mesa['estado'] == 'activa') echo 'selected'; ?>>Activa</option>
                <option value="inactiva" <?php if ($mesa['estado'] == 'inactiva') echo 'selected'; ?>>Inactiva</option>
                <option value="ocupada" <?php if ($mesa['estado'] == 'ocupada') echo 'selected'; ?>>Ocupada</option>
            </select>
            <button type="submit">Actualizar</button>
        </form>
    </section>
</body>
</html>
