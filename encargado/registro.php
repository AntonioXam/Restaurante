<?php

// Incluir archivos de sesión y conexión
include 'sesion_encargado.php';
include '../conexion.php';

// Manejar la solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y asignar variables del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol']; // Nuevo campo para el rol
    
    // Procesar la imagen
    $foto = null;
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $foto = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            // Imagen subida correctamente
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    $sql = "INSERT INTO usuarios (nombre, apellidos, dni, usuario, contrasena, rol, foto, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssss", $nombre, $apellido, $dni, $usuario, $contrasena, $rol, $foto);
    
    if ($stmt->execute()) {
        header("Location: listar_camareros.php");
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}
?>

<!-- Sección HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
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
            /* ...existing styles... */
        }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- Contenido del formulario -->
    <div class="col-12 col-md-8 offset-md-2">
        <div class="form-card">
            <h2 class="h4 mb-4 text-center">Registrar Nuevo Usuario</h2>
            <form action="registro.php" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
                <!-- Campos del formulario -->
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <!-- ...existing form fields... -->
                </div>
                <!-- ...más campos... -->
                <button type="submit" class="btn btn-primary btn-block btn-lg mt-4">Registrar</button>
            </form>
        </div>
    </div>
    
    <!-- Scripts de JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Script de validación personalizado -->
    <script>
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
