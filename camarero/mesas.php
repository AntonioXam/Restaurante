<?php
include '../sesion.php';
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar el número actual de mesas
    $query_count = "SELECT COUNT(*) as total FROM mesas";
    $result_count = mysqli_query($conexion, $query_count);
    $count = mysqli_fetch_assoc($result_count)['total'];

    if ($count < 6) {
        // Obtener datos del formulario
        $numero_mesa = $_POST['numero_mesa'];
        $comensales = $_POST['comensales'];
        $estado = 'activa'; // Estado por defecto al crear una mesa

        // Insertar nueva mesa
        $query = "INSERT INTO mesas (numero_mesa, comensales, estado) VALUES ('$numero_mesa', '$comensales', '$estado')";
        mysqli_query($conexion, $query);

        // Redirigir a la página de mesas
        header("Location: index.php");
    } else {
        echo "El número máximo de mesas (6) ha sido alcanzado.";
    }
}

// Obtener mesas
$query = "SELECT * FROM mesas";
$result = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mesas</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Gestión de Mesas</h1>
    </header>
    <section>
        <h2>Agregar Mesa</h2>
        <form method="POST">
            <label for="numero_mesa">Número de Mesa:</label>
            <input type="text" name="numero_mesa" id="numero_mesa" required>
            <label for="comensales">Comensales:</label>
            <input type="number" name="comensales" id="comensales" required>
            <button type="submit">Agregar</button>
        </form>
        <h2>Mesas Actuales</h2>
        <table>
            <tr>
                <th>Número de Mesa</th>
                <th>Comensales</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['numero_mesa']; ?></td>
                <td><?php echo $row['comensales']; ?></td>
                <td><?php echo $row['estado']; ?></td>
                <td>
                    <a href="editar_mesa.php?id=<?php echo $row['id']; ?>">Editar</a>
                    <a href="eliminar_mesa.php?id=<?php echo $row['id']; ?>">Eliminar</a>
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
