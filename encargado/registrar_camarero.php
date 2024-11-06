<?php 
include '../sesion.php';
include '../conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Camarero</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <section>
            <h2>Registrar Camarero</h2>
            <form action="registro.php" method="post" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                    <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" class="form-control" name="apellido" id="apellido" required>
                    <div class="invalid-feedback">Por favor, ingrese el apellido.</div>
                </div>
                <div class="form-group">
                    <label for="dni">DNI:</label>
                    <input type="text" class="form-control" name="dni" id="dni" maxlength="9" required>
                    <div class="invalid-feedback">Por favor, ingrese el DNI.</div>
                </div>
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" class="form-control" name="usuario" id="usuario" required>
                    <div class="invalid-feedback">Por favor, ingrese el usuario.</div>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" name="contrasena" id="contrasena" required>
                    <div class="invalid-feedback">Por favor, ingrese la contraseña.</div>
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </form>
        </section>
    </div>
    <!-- bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>