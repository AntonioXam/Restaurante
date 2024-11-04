<?php

include '../sesion.php';
include '../conexion.php';

// hacer consulta a la base de datos listado de los camareros
$sql = "SELECT * FROM usuarios WHERE rol = 'camarero'";
$resultado = $conexion->query($sql);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Camareros</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
    <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Restaurante</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Volver</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="my-4">Listado de Camareros</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($camarero = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $camarero['nombre']; ?></td>
                        <td><?php echo $camarero['apellidos']; ?></td>
                        <td><?php echo $camarero['dni']; ?></td>
                        <td><?php echo $camarero['usuario']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary mt-4">Volver</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
// cerrar la conexion
$conexion->close();
?>
