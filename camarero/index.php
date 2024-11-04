<?php
include '../sesion.php';
include '../conexion.php';

// Obtener mesas
$query = "SELECT * FROM mesas";
$result = mysqli_query($conexion, $query);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Camarero</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <h1>Bienvenido, Camarero</h1>
    </header>
    <section>
        <h2>Gestión de Mesas</h2>
        <h3>Mesas Actuales</h3>
        <table>
            <tr>
                <th>Número de Mesa</th>
                <th>Estado</th>
                <th>Comensales</th>
                <th>Acciones</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['numero_mesa']; ?></td>
                <td><?php echo $row['estado']; ?></td>
                <td><?php echo $row['comensales']; ?></td>
                <td>
                    <?php if ($row['estado'] == 'activa') { ?>
                        <a href="pedidos.php?mesa_id=<?php echo $row['id']; ?>">Gestionar Pedido</a>
                    <?php } ?>
                    <a href="editar_mesa.php?id=<?php echo $row['id']; ?>">Editar</a>
                    <a href="eliminar_mesa.php?id=<?php echo $row['id']; ?>">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        <h3>Agregar Mesa</h3>
        <form method="POST" action="mesas.php">
            <label for="numero_mesa">Número de Mesa:</label>
            <input type="text" name="numero_mesa" id="numero_mesa" required>
            <label for="comensales">Comensales:</label>
            <input type="number" name="comensales" id="comensales" required>
            <button type="submit">Agregar</button>
        </form>
    </section>
    <footer>
        <button onclick="location.href='../logout.php'">Cerrar Sesión</button>
        <button onclick="history.back()">Volver</button>
    </footer>
</body>
</html>
