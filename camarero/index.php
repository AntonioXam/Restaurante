<?php
include '../sesion.php';
include '../conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Camarero</title>
     <!-- bootstrap -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header>
        <h1>Bienvenido, Camarero</h1>
    </header>
    <nav>
        <ul>
            <li><a href="gestionar_mesas.php">Gestionar Mesas</a></li>
            <li><a href="gestionar_pedido.php">Gestionar Pedidos</a></li>
            <li><a href="../logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <section>
        <h2>Panel de Camarero</h2>
        <p>Seleccione una opción del menú para comenzar.</p>
    </section>
</body>
</html>
