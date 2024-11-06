<?php
include '../sesion.php';
include '../conexion.php';

// Funciones
function obtener_mesas_activas($conexion) {
    return mysqli_query($conexion, "SELECT * FROM mesas WHERE estado = 'activa'");
}

// Lógica para activar mesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];
    $comensales = $_POST['comensales'];

    mysqli_query($conexion, "UPDATE mesas SET estado = 'activa', comensales = $comensales WHERE id = $mesa_id");
    mysqli_query($conexion, "INSERT INTO pedidos (mesa_id, estado, total) VALUES ($mesa_id, 'pendiente', 0.00)");

    header("Location: gestionar_pedido.php?mesa_id=$mesa_id");
    exit();
}

$mesa_id = isset($_GET['mesa_id']) ? $_GET['mesa_id'] : null;
$mesas_inactivas_result = mysqli_query($conexion, "SELECT * FROM mesas WHERE estado = 'inactiva'");
$mesas_activas_result = obtener_mesas_activas($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Mesas - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Restaurante</a>
            <div class="d-flex align-items-center">
                <a href="index.php" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Mesas Activas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Mesas Activas</h5>
                        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
                            <?php 
                            $mesas_activas = obtener_mesas_activas($conexion);
                            while ($mesa = mysqli_fetch_assoc($mesas_activas)):
                            ?>
                            <div class="col">
                                <div class="card h-100 mesa-card active">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chair fa-2x mb-2 text-primary"></i>
                                        <h5 class="card-title">Mesa <?php echo $mesa['numero_mesa']; ?></h5>
                                        <p class="card-text"><small class="text-muted"><?php echo $mesa['comensales']; ?> comensales</small></p>
                                        <a href="gestionar_pedido.php?mesa_id=<?php echo $mesa['id']; ?>" 
                                           class="btn btn-primary btn-sm w-100">Gestionar</a>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mesas Inactivas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Mesas Disponibles</h5>
                        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
                            <?php while ($mesa = mysqli_fetch_assoc($mesas_inactivas_result)): ?>
                            <div class="col">
                                <div class="card h-100 mesa-card inactive" 
                                     onclick="seleccionarMesa(<?php echo $mesa['id']; ?>, <?php echo $mesa['numero_mesa']; ?>)">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chair fa-2x mb-2 text-secondary"></i>
                                        <h5 class="card-title">Mesa <?php echo $mesa['numero_mesa']; ?></h5>
                                        <p class="card-text"><small class="text-muted">Disponible</small></p>
                                        <button class="btn btn-outline-primary btn-sm w-100">Activar Mesa</button>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Activar Mesa -->
    <div class="modal fade" id="activarMesaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Activar Mesa <span id="numeroMesa"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="mesa_id" id="mesaId">
                        <div class="form-group">
                            <label for="comensales" class="form-label">Número de Comensales:</label>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary" onclick="ajustarComensales(-1)">-</button>
                                <input type="number" class="form-control text-center" id="comensales" name="comensales" 
                                       value="1" min="1" max="12" style="max-width: 80px;" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="ajustarComensales(1)">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="activar_mesa" class="btn btn-primary">Activar Mesa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    .mesa-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    
    .mesa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .mesa-card.active .fa-chair {
        color: #0d6efd;
    }
    
    .mesa-card.inactive .fa-chair {
        color: #6c757d;
    }
    
    @media (max-width: 576px) {
        .mesa-card .card-body {
            padding: 1rem;
        }
        
        .mesa-card .fa-2x {
            font-size: 1.5em;
        }
        
        .mesa-card .card-title {
            font-size: 1rem;
        }
        
        .mesa-card .card-text {
            font-size: 0.8rem;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function seleccionarMesa(mesaId, numeroMesa) {
        document.getElementById('mesaId').value = mesaId;
        document.getElementById('numeroMesa').textContent = numeroMesa;
        document.getElementById('comensales').value = 1;
        new bootstrap.Modal(document.getElementById('activarMesaModal')).show();
    }

    function ajustarComensales(cambio) {
        const input = document.getElementById('comensales');
        const nuevoValor = parseInt(input.value) + cambio;
        if (nuevoValor >= 1 && nuevoValor <= 12) {
            input.value = nuevoValor;
        }
    }
    </script>
</body>
</html>
