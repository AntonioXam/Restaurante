<?php 
include '../sesion.php';
include '../conexion.php';
// ...existing code...
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Camarero</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .back-button {
            margin: 10px 0;
        }
        @media (max-width: 768px) {
            .btn-back {
                width: 100%;
                padding: 12px;
            }
            .container { padding: 0 15px; }
            .form-group { margin-bottom: 1rem; }
        }
    </style>
    <!-- Agregar FontAwesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>
    
    <div class="container mt-3 mt-lg-5">
        <a href="index.php" class="btn btn-secondary btn-lg back-button mb-4 btn-back">
            <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
        
        <section>
            <h2 class="h4 mb-4">Registrar Camarero</h2>
            <form action="registro.php" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
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
                <div class="form-group">
                    <label for="foto">Foto:</label>
                    <input type="file" class="form-control-file" name="foto" id="foto" accept="image/*">
                    <small class="form-text text-muted">Seleccione una imagen para el camarero (opcional)</small>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">Registrar</button>
            </form>
        </section>
    </div>
    <!-- bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>