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
    <title>Panel de Encargado</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .nav-button {
            margin: 10px 0;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            .btn-nav {
                width: 100%;
                padding: 15px;
                font-size: 1.1rem;
            }
            .card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>
    
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="d-flex flex-column">
                    <a href="registrar_camarero.php" class="btn btn-primary btn-lg nav-button mb-2">
                        <i class="fas fa-user-plus"></i> Registrar Camarero
                    </a>
                    <a href="listar_camareros.php" class="btn btn-info btn-lg nav-button mb-2">
                        <i class="fas fa-list"></i> Listar Camareros
                    </a>
                    <a href="../logout.php" class="btn btn-danger btn-lg nav-button">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">Panel de Control - Encargado</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">
                                            <i class="fas fa-user-plus mb-2"></i> Registro
                                        </h5>
                                        <p class="card-text">Añade nuevos camareros al sistema de forma rápida y sencilla.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                        <h5 class="card-title text-info">
                                            <i class="fas fa-list mb-2"></i> Gestión
                                        </h5>
                                        <p class="card-text">Visualiza y administra la información de todos los camareros.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title text-success">
                                            <i class="fas fa-shield-alt mb-2"></i> Seguridad
                                        </h5>
                                        <p class="card-text">Control de acceso y gestión segura de las credenciales.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Utiliza los botones superiores para navegar entre las diferentes funciones.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Agregar FontAwesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- bootstrap scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
