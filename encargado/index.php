<?php
include '../sesion.php';
include '../conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Encargado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #007bff;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h3">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>

    <div class="container mt-3">
        <div class="row">
            <div class="col-12 col-md-4 mb-3">
                <div class="action-card card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-plus card-icon"></i>
                        <h5>Registrar Camarero</h5>
                        <p class="text-muted">Añade nuevos camareros al sistema</p>
                        <a href="registrar_camarero.php" class="btn btn-primary btn-block">Registrar</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-3">
                <div class="action-card card">
                    <div class="card-body text-center">
                        <i class="fas fa-list card-icon"></i>
                        <h5>Listar Camareros</h5>
                        <p class="text-muted">Ver y gestionar camareros existentes</p>
                        <a href="listar_camareros.php" class="btn btn-info btn-block">Listar</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-3">
                <div class="action-card card">
                    <div class="card-body text-center">
                        <i class="fas fa-sign-out-alt card-icon"></i>
                        <h5>Cerrar Sesión</h5>
                        <p class="text-muted">Salir del sistema de forma segura</p>
                        <a href="../logout.php" class="btn btn-danger btn-block">Salir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
