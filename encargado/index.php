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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .nav-button {
            margin: 8px 0;
            transition: all 0.3s ease;
        }
        .nav-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card {
            transition: transform 0.3s ease;
            margin-bottom: 1rem;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .action-buttons {
            max-width: 600px;
            margin: 0 auto;
        }
        @media (min-width: 768px) {
            .container {
                max-width: 90%;
            }
            .nav-button {
                padding: 12px 24px;
                font-size: 1rem;
            }
            .card-deck {
                margin-right: -10px;
                margin-left: -10px;
            }
            .card {
                margin: 0 10px 20px;
                flex: 0 0 calc(33.333% - 20px);
            }
        }
        .icon-feature {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #007bff;
        }
    </style>
</head>
<body class="bg-light">
    <header class="bg-primary text-white text-center py-4 mb-4">
        <h1 class="h3 mb-0">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>
    
    <div class="container">
        <div class="action-buttons mb-4">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <a href="registrar_camarero.php" class="btn btn-primary btn-block nav-button">
                        <i class="fas fa-user-plus"></i> Registrar Camarero
                    </a>
                </div>
                <div class="col-sm-12 col-md-4">
                    <a href="listar_camareros.php" class="btn btn-info btn-block nav-button">
                        <i class="fas fa-list"></i> Listar Camareros
                    </a>
                </div>
                <div class="col-sm-12 col-md-4">
                    <a href="../logout.php" class="btn btn-danger btn-block nav-button">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">Panel de Control - Encargado</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="icon-feature">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h5 class="card-title">Gestión de Personal</h5>
                                <p class="card-text">Administra el registro y control de camareros de forma eficiente.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="icon-feature">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h5 class="card-title">Supervisión</h5>
                                <p class="card-text">Monitorea el desempeño y la actividad del personal.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="icon-feature">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h5 class="card-title">Seguridad</h5>
                                <p class="card-text">Control de acceso y gestión segura de credenciales.</p>
                            </div>
                        </div>
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
