<?php
include '../sesion.php';
include '../conexion.php';

// Consulta para obtener el historial de cuentas pagadas agrupadas
$query = "SELECT 
            cp.mesa_id,
            m.numero_mesa,
            MIN(cp.fecha_hora) as fecha_hora,
            COUNT(*) as num_productos,
            SUM(cp.subtotal) as total
          FROM cuentas_pagadas cp
          INNER JOIN mesas m ON cp.mesa_id = m.id
          GROUP BY cp.mesa_id, cp.fecha_hora
          ORDER BY cp.fecha_hora DESC 
          LIMIT 10";
$historial_result = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Restaurante - Camarero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-utensils me-2"></i>
                Restaurante
            </a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">Bienvenido, <?php echo $_SESSION['nombre']; ?></span>
                <a href="../logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-2"></i>Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm hover-card mb-4">
                    <div class="card-body p-0">
                        <a href="gestionar_mesas.php" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-center p-4">
                                <div class="icon-box bg-primary bg-opacity-10 rounded-3 me-3">
                                    <i class="fas fa-chair text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="h5 mb-1">Gestionar Mesas</h3>
                                    <p class="text-muted small mb-0">Gestionar mesas, pedidos y cuentas</p>
                                </div>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm hover-card mb-4">
                    <div class="card-body p-0">
                        <a href="cuentas_pagadas.php" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-center p-4">
                                <div class="icon-box bg-success bg-opacity-10 rounded-3 me-3">
                                    <i class="fas fa-history text-success"></i>
                                </div>
                                <div>
                                    <h3 class="h5 mb-1">Historial de Cuentas</h3>
                                    <p class="text-muted small mb-0">Ver historial de cuentas pagadas</p>
                                </div>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Historial -->
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Últimas Cuentas Pagadas
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if ($historial_result && mysqli_num_rows($historial_result) > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php while ($pago = mysqli_fetch_assoc($historial_result)): ?>
                                    <div class="list-group-item hover-list-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Mesa <?php echo $pago['numero_mesa']; ?></h6>
                                                <div class="d-flex align-items-center text-muted small">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?php echo date('d/m/Y H:i', strtotime($pago['fecha_hora'])); ?>
                                                    <i class="fas fa-shopping-basket ms-2 me-1"></i>
                                                    <?php echo $pago['num_productos']; ?> productos
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success rounded-pill">
                                                    <?php echo number_format($pago['total'], 2); ?>€
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay historial de pagos</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    :root {
        --restaurant-primary: #2c3e50;    /* Azul oscuro principal */
        --restaurant-secondary: #34495e;   /* Azul oscuro secundario */
        --restaurant-accent: #3498db;      /* Azul claro para acentos */
        --restaurant-light: #ecf0f1;       /* Gris muy claro para fondos */
        --restaurant-dark: #1a252f;        /* Azul muy oscuro */
    }

    body {
        background-color: var(--restaurant-light);
    }

    .navbar {
        background: var(--restaurant-dark) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Cards principales */
    .hover-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(44, 62, 80, 0.15);
    }

    /* Iconos y elementos visuales */
    .icon-box {
        background-color: var(--restaurant-accent);
        color: white;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .hover-card:hover .icon-box {
        transform: scale(1.1);
        background-color: var(--restaurant-primary);
    }

    /* Historial de cuentas */
    .list-group-item {
        border: none;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }

    .list-group-item:hover {
        background-color: var(--restaurant-light);
        border-left-color: var(--restaurant-accent);
        transform: translateX(5px);
    }

    .badge {
        background-color: var(--restaurant-accent) !important;
    }

    /* Botones y acciones */
    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: white;
    }

    @media (max-width: 576px) {
        .icon-box {
            width: 40px;
            height: 40px;
        }

        .icon-box i {
            font-size: 1.2rem;
        }

        .card-body {
            padding: 0.75rem;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function verDetalle(detalleJson) {
        const detalle = JSON.parse(detalleJson);
        let html = '';
        let total = 0;

        detalle.forEach(item => {
            html += `
            <tr>
                <td>${item.producto}</td>
                <td class="text-center">${item.cantidad}</td>
                <td class="text-end">${item.precio_unitario.toFixed(2)}€</td>
                <td class="text-end">${item.subtotal.toFixed(2)}€</td>
            </tr>`;
            total += parseFloat(item.subtotal);
        });

        html += `
        <tr class="table-light">
            <td colspan="3" class="text-end"><strong>Total:</strong></td>
            <td class="text-end"><strong>${total.toFixed(2)}€</strong></td>
        </tr>`;

        document.getElementById('detalleBody').innerHTML = html;
        new bootstrap.Modal(document.getElementById('detalleModal')).show();
    }

    function verDetallePago(pagoId) {
        // Cargar detalles mediante AJAX
        fetch('obtener_detalle_pago.php?pago_id=' + pagoId)
            .then(response => response.json())
            .then(data => {
                let html = '';
                let total = 0;
                
                data.forEach(item => {
                    html += `
                    <tr>
                        <td>${item.producto}</td>
                        <td class="text-center">${item.cantidad}</td>
                        <td class="text-end">${parseFloat(item.precio_unitario).toFixed(2)}€</td>
                        <td class="text-end">${parseFloat(item.subtotal).toFixed(2)}€</td>
                    </tr>`;
                    total += parseFloat(item.subtotal);
                });

                document.getElementById('detalleBody').innerHTML = html;
                new bootstrap.Modal(document.getElementById('detalleModal')).show();
            });
    }
    </script>
</body>
</html>
