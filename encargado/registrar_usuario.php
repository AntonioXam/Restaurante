<?php
// Incluimos el archivo de verificación de sesión de encargado
include 'sesion_encargado.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metadatos y títulos -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <style>
        /* Estilos responsivos */
        @media (max-width: 768px) {
            .container { 
                padding: 10px; 
            }
            .btn-block {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        .form-card {
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            background: white;
        }
        .form-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .form-group label {
            font-weight: 500;
            color: #2c3e50;
        }
        .form-control {
            border-radius: 8px;
        }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Cabecera que muestra el nombre del usuario logueado -->
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>

    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <a href="index.php" class="btn btn-secondary btn-block mb-4">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
            </div>
            <div class="col-12 col-md-8 offset-md-2">
                <div class="form-card">
                    <h2 class="h4 mb-4 text-center">Registrar Nuevo Usuario</h2>
                    <!-- Formulario de registro con validación del lado del cliente -->
                    <form action="registro.php" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                        <!-- Campos del formulario -->
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
                            <label for="rol">Rol:</label>
                            <select class="form-control" name="rol" id="rol" required>
                                <option value="">Seleccione un rol</option>
                                <option value="camarero">Camarero</option>
                                <option value="encargado">Encargado</option>
                            </select>
                            <div class="invalid-feedback">Por favor, seleccione el rol.</div>
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto:</label>
                            <input type="file" class="form-control-file" name="foto" id="foto" accept="image/*">
                            <small class="form-text text-muted">Seleccione una imagen para el usuario (opcional)</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de validación del formulario -->
    <script>
        // Función auto-ejecutable para la validación de Bootstrap
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                // Selecciona todos los formularios que necesitan validación
                var forms = document.getElementsByClassName('needs-validation');
                
                // Aplica la validación a cada formulario
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        // Previene el envío si hay campos inválidos
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        // Añade las clases de validación para mostrar el feedback
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>