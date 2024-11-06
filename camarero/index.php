<?php
include '../sesion.php';
include '../conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Restaurante - Camarero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1 class="h4 mb-0">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    </header>

    <div class="container py-4">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <a href="gestionar_mesas.php" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chair fs-1 mb-2 text-primary"></i>
                            <h3 class="card-title">Gestionar Mesas</h3>
                            <p class="card-text">Asignar y gestionar mesas de clientes</p>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-12 col-md-6">
                <a href="gestionar_pedido.php" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-utensils fs-1 mb-2 text-primary"></i>
                            <h3 class="card-title">Gestionar Pedidos</h3>
                            <p class="card-text">Tomar y gestionar pedidos de clientes</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <h3 class="card-title">Funciones del Camarero</h3>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="p-3 border rounded text-center">
                                    <i class="fas fa-chair mb-2"></i>
                                    <h4 class="h5">Asignar Mesas</h4>
                                    <p class="small mb-0">Asignar mesas a los clientes</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 border rounded text-center">
                                    <i class="fas fa-clipboard-list mb-2"></i>
                                    <h4 class="h5">Asignar Productos</h4>
                                    <p class="small mb-0">Tomar pedidos y asignar productos</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 border rounded text-center">
                                    <i class="fas fa-paper-plane mb-2"></i>
                                    <h4 class="h5">Enviar Pedido</h4>
                                    <p class="small mb-0">Enviar pedidos a cocina</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="../logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>
