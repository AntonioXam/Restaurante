<?php
include '../sesion.php';
include '../conexion.php';

// Obtener mesas activas
$query = "SELECT * FROM mesas WHERE estado = 'activa'";
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
    <nav>
        <ul>
            <li><a href="../logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <section>
        <h2>Gestión de Mesas</h2>
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
                <td><a href="pedidos.php?mesa_id=<?php echo $row['id']; ?>">Gestionar Pedido</a></td>
            </tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>
