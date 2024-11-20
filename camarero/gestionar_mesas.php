<?php
include '../sesion.php';
include '../conexion.php';

// Gestión de mesas del restaurante
// Muestra:
// - Mesas activas (ocupadas)
// - Mesas inactivas (disponibles)
// Permite:
// - Activar mesas nuevas
// - Asignar número de comensales
// - Acceder a la gestión de pedidos de cada mesa

// Funciones principales:
// - Obtener mesas activas
// - Activar mesas nuevas
// - Gestionar interfaz visual de mesas
// - Modal para configurar nuevas mesas

// Variables principales:
// $mesa_id - ID de la mesa seleccionada
// $mesas_inactivas_result - Resultado de mesas disponibles
// $mesas_activas_result - Resultado de mesas ocupadas
// $comensales - Número de comensales para una mesa

/**
 * Obtiene todas las mesas que están actualmente ocupadas
 * @param mysqli $conexion - Conexión a la base de datos
 * @return mysqli_result - Resultado de la consulta
 */
function obtener_mesas_activas($conexion) {
    return mysqli_query($conexion, "SELECT * FROM mesas WHERE estado = 'activa'");
}

/**
 * Verifica si una mesa tiene productos en la cuenta
 * @param mysqli $conexion - Conexión a la base de datos
 * @param int $mesa_id - ID de la mesa
 * @return bool - True si tiene productos, False en caso contrario
 */
function mesa_tiene_productos($conexion, $mesa_id) {
    $query = "SELECT COUNT(*) as total FROM cuenta WHERE mesa_id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $mesa_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] > 0;
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

/**
 * Proceso de activación de mesa:
 * 1. Actualización del estado de la mesa a 'activa'
 * 2. Asignación del número de comensales
 * 3. Creación de nuevo pedido pendiente
 * 4. Redirección a gestión de pedido
 */
// Lógica de activación de mesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['activar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];
    $comensales = $_POST['comensales'];

    mysqli_query($conexion, "UPDATE mesas SET estado = 'activa', comensales = $comensales WHERE id = $mesa_id");
    mysqli_query($conexion, "INSERT INTO pedidos (mesa_id, estado, total) VALUES ($mesa_id, 'pendiente', 0.00)");

    header("Location: gestionar_pedido.php?mesa_id=$mesa_id");
    exit();
   
}

// Lógica para cerrar mesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cerrar_mesa'])) {
    $mesa_id = $_POST['mesa_id'];
    if (!mesa_tiene_productos($conexion, $mesa_id)) {
        mysqli_query($conexion, "UPDATE mesas SET estado = 'inactiva', comensales = NULL WHERE id = $mesa_id");
        header("Location: gestionar_mesas.php?status=success");
        exit();
    }
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
    <style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #3498db;
        --success-color: #27ae60;
        --light-color: #ecf0f1;
        --dark-color: #2c3e50;
    }

    body {
        background-color: var(--light-color);
    }

    .navbar {
        background: var(--primary-color) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        background: var(--primary-color) !important;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
    }

    .mesa-card {
        transition: all 0.3s ease;
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }

    .mesa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .mesa-card.active {
        border: 2px solid var(--accent-color);
    }

    .mesa-card .fa-chair {
        color: var(--accent-color);
        transition: all 0.3s ease;
    }

    .mesa-card:hover .fa-chair {
        transform: scale(1.1);
    }

    .btn-primary {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        padding: 0.5rem 1.2rem;
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        transform: translateY(-1px);
    }

    .btn-outline-primary {
        color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--accent-color);
        color: white;
    }

    .modal-header {
        background: var(--primary-color);
        color: white;
        border-radius: 8px 8px 0 0;
    }

    .modal-content {
        border-radius: 8px;
        border: none;
    }

    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .mesa-card .card-body {
            padding: 1rem;
        }

        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
    }

    /* Mejoras de accesibilidad */
    .btn {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .card-title {
        font-weight: 600;
        color: var(--dark-color);
    }

    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
    }

    /* Animaciones suaves */
    .btn, .card, .mesa-card, .fa-chair {
        transition: all 0.3s ease-in-out;
    }

    .mesa-card.active {
        background: white;
        border: none !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .mesa-card .mesa-icon {
        position: relative;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mesa-card.active .fa-chair {
        color: var(--accent-color);
    }

    .badge {
        font-size: 0.7rem;
        padding: 0.35em 0.65em;
        border-radius: 4px;
    }

    .mesa-card .btn-primary,
    .mesa-card .btn-outline-primary {
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }

    @media (max-width: 768px) {
        .mesa-card .card-body {
            padding: 1rem !important;
        }
        
        .mesa-card .badge {
            font-size: 0.65rem;
        }
        
        .mesa-card .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
    }

    .btn-outline-danger.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        position: relative;
    }

    .mesa-estado {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    .mesa-card .botones-mesa {
        display: grid;
        gap: 0.5rem;
    }

    .mesa-card .botones-mesa .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        font-size: 0.875rem;
        width: 100%;
    }

    .mesa-card .botones-mesa i {
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .mesa-estado {
        font-size: 0.65rem;
        padding: 0.15rem 0.5rem;
        margin-left: 0.5rem;
        display: inline-block;
        vertical-align: middle;
    }

    @media (max-width: 576px) {
        .mesa-card .botones-mesa {
            gap: 0.35rem;
        }

        .mesa-card .botones-mesa .btn {
            font-size: 0.8rem;
            padding: 0.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mesa-card .botones-mesa .btn i {
            font-size: 0.9rem;
            margin-right: 0.3rem;
        }

        .mesa-estado {
            position: static;
            display: inline-flex;
            align-items: center;
            margin-left: 0.3rem;
            font-size: 0.65rem;
            padding: 0.1rem 0.3rem;
        }

        .mesa-card .card-body {
            padding: 0.75rem !important;
        }

        .mesa-card h5.card-title {
            font-size: 1rem;
        }

        .mesa-card .card-text {
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
        }
    }
    </style>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-utensils me-2"></i>
                Restaurante
            </a>
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
                    <div class="card-header py-3">
                        <h5 class="card-title mb-0 text-white">
                            <i class="fas fa-chair me-2"></i>
                            Mesas Ocupadas
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-4">
                            <?php 
                            $mesas_activas = obtener_mesas_activas($conexion);
                            while ($mesa = mysqli_fetch_assoc($mesas_activas)): 
                                $tiene_productos = mesa_tiene_productos($conexion, $mesa['id']);
                            ?>
                            <div class="col">
                                <div class="card h-100 mesa-card active border-0">
                                    <div class="card-body text-center p-3">
                                        <div class="mesa-icon mb-3">
                                            <i class="fas fa-chair fa-2x"></i>
                                            <span class="badge bg-success position-absolute top-0 end-0 mt-2 me-2">
                                                Ocupada
                                            </span>
                                        </div>
                                        <h5 class="card-title h6 mb-2">Mesa <?php echo $mesa['numero_mesa']; ?></h5>
                                        <p class="card-text text-muted mb-3">
                                            <small><i class="fas fa-users me-1"></i><?php echo $mesa['comensales']; ?> comensales</small>
                                        </p>
                                        <div class="botones-mesa">
                                            <a href="gestionar_pedido.php?mesa_id=<?php echo $mesa['id']; ?>" 
                                               class="btn btn-primary d-flex align-items-center justify-content-center">
                                               <i class="fas fa-utensils"></i>
                                               <span>Gestionar</span>
                                            </a>
                                            <a href="cuenta.php?mesa_id=<?php echo $mesa['id']; ?>" 
                                               class="btn btn-outline-primary d-flex align-items-center justify-content-center">
                                               <i class="fas fa-receipt"></i>
                                               <span>Cuenta</span>
                                            </a>
                                            <?php if (!$tiene_productos): ?>
                                                <button onclick="cerrarMesa(<?php echo $mesa['id']; ?>)" 
                                                        class="btn btn-outline-danger d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-door-open"></i>
                                                    <span>Cerrar</span>
                                                    <span class="badge bg-success mesa-estado">
                                                        Libre
                                                    </span>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-danger disabled d-flex align-items-center justify-content-center" 
                                                        disabled
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="No se puede cerrar la mesa mientras tenga productos pendientes">
                                                    <i class="fas fa-door-open"></i>
                                                    <span>Cerrar</span>
                                                    <span class="badge bg-warning mesa-estado">
                                                        Ocupada
                                                    </span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
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
                                        <i class="fas fa-chair fa-2x mb-2"></i>
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

    <!-- Modal Cerrar Mesa -->
    <div class="modal fade" id="cerrarMesaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Cierre de Mesa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center mb-1">¿Está seguro que desea cerrar esta mesa?</p>
                    <p class="text-center text-muted small">Esta acción liberará la mesa para nuevos clientes</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="" method="POST">
                        <input type="hidden" name="mesa_id" id="cerrarMesaId">
                        <input type="hidden" name="cerrar_mesa" value="1">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-door-open me-2"></i>
                            Cerrar Mesa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    /**
     * Función JavaScript para seleccionar una mesa para activar
     * @param int mesaId - ID de la mesa
     * @param int numeroMesa - Número identificador de la mesa
     */
    function seleccionarMesa(mesaId, numeroMesa) {
        document.getElementById('mesaId').value = mesaId;
        document.getElementById('numeroMesa').textContent = numeroMesa;
        document.getElementById('comensales').value = 1;
        new bootstrap.Modal(document.getElementById('activarMesaModal')).show();
    }

    /**
     * Función JavaScript para ajustar el número de comensales
     * @param int cambio - Valor de incremento/decremento (+1 o -1)
     */
    function ajustarComensales(cambio) {
        const input = document.getElementById('comensales');
        const nuevoValor = parseInt(input.value) + cambio;
        if (nuevoValor >= 1 && nuevoValor <= 12) {
            input.value = nuevoValor;
        }
    }

    function cerrarMesa(mesaId) {
        document.getElementById('cerrarMesaId').value = mesaId;
        new bootstrap.Modal(document.getElementById('cerrarMesaModal')).show();
    }

    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    </script>
</body>
</html>
