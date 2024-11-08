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
    <style>
        @media (max-width: 768px) {
            .table-responsive { font-size: 0.9rem; }
        }
        .back-button {
            margin: 10px 0;
        }
        @media (max-width: 768px) {
            .btn-back {
                width: 100%;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>

    <div class="container mt-3 mt-lg-5">
        <a href="index.php" class="btn btn-secondary btn-lg back-button mb-4 btn-back">
            <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>

        <h2 class="h4 mb-4">Listado de Camareros</h2>
        <div class="table-responsive">
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
        </div>
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
