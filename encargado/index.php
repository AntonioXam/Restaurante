<?php

// Incluir archivos de sesión y conexión
include 'sesion_encargado.php';
include '../conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metadatos y título -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Encargado</title>
    <!-- CSS de Bootstrap y Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            h1 { 
                font-size: 1.5rem; 
                margin-bottom: 20px; 
            }
        }
        .action-card {
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ffffff;
        }
        .card-primary {
            background-color: #007bff;
            border: none;
        }
        .card-info {
            background-color: #17a2b8;
            border: none;
        }
        .card-success {
            background-color: #28a745;
            border: none;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Cabecera -->
    <header class="bg-primary text-white py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </a>
        </div>
    </header>

    <!-- Panel de acciones -->
    <div class="container mt-4">
        <div class="row">
            <!-- Tarjeta Registrar Usuario -->
            <div class="col-12 col-md-3 mb-4">
                <div class="action-card card text-white card-primary card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-user-plus card-icon"></i>
                        <h5>Registrar Usuario</h5>
                        <p class="text-light">Añade nuevos usuarios al sistema</p>
                        <a href="registrar_usuario.php" class="btn btn-light mt-3"><i class="fas fa-arrow-right"></i> Registrar</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Listar Usuarios -->
            <div class="col-12 col-md-3 mb-4">
                <div class="action-card card text-white card-info card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-list card-icon"></i>
                        <h5>Listar Usuarios</h5>
                        <p class="text-light">Ver y gestionar usuarios existentes</p>
                        <a href="listar_usuarios.php" class="btn btn-light mt-3"><i class="fas fa-arrow-right"></i> Listar</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Gestionar Camareros -->
            <div class="col-12 col-md-3 mb-4">
                <div class="action-card card text-white card-success card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-concierge-bell card-icon"></i>
                        <h5>Gestionar Camareros</h5>
                        <p class="text-light">Ver y administrar camareros</p>
                        <a href="listar_usuarios.php?rol=camarero" class="btn btn-light mt-3"><i class="fas fa-arrow-right"></i> Gestionar</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Acceder como Camarero -->
            <div class="col-12 col-md-3 mb-4">
                <div class="action-card card text-white bg-secondary card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-hamburger card-icon"></i>
                        <h5>Acceder como Camarero</h5>
                        <p class="text-light">Gestionar tareas como camarero</p>
                        <a href="../camarero/index.php" class="btn btn-light mt-3"><i class="fas fa-arrow-right"></i> Entrar</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Gestionar Productos -->
            <div class="col-12 col-md-3 mb-4">
                <div class="action-card card text-white bg-warning card-hover">
                    <div class="card-body text-center">
                        <i class="fas fa-box-open card-icon"></i>
                        <h5>Gestionar Productos</h5>
                        <p class="text-light">Añadir, modificar y eliminar productos</p>
                        <a href="listar_productos.php" class="btn btn-light mt-3"><i class="fas fa-arrow-right"></i> Gestionar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
