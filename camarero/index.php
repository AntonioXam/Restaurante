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
    <header class="bg-primary text-white text-center py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </a>
        </div>
    </header>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <a href="gestionar_mesas.php" class="text-decoration-none mb-3 d-block">
                    <div class="card shadow-sm hover-effect">
                        <div class="card-body text-center p-5">
                            <i class="fas fa-chair fs-1 mb-3 text-primary"></i>
                            <h3 class="card-title h4">Gestionar Mesas</h3>
                            <p class="card-text text-muted">Gestionar mesas, pedidos y cuentas</p>
                        </div>
                    </div>
                </a>

                <a href="cuentas_pagadas.php" class="text-decoration-none">
                    <div class="card shadow-sm hover-effect">
                        <div class="card-body text-center p-5">
                            <i class="fas fa-history fs-1 mb-3 text-primary"></i>
                            <h3 class="card-title h4">Historial de Cuentas</h3>
                            <p class="card-text text-muted">Ver historial de cuentas pagadas</p>
                        </div>
                    </div>
                </a>

                <!-- Nueva sección de historial -->
                <div class="card mt-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>
                            Últimas Cuentas Pagadas
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if ($historial_result && mysqli_num_rows($historial_result) > 0): ?>
                            <div class="list-group list-group-flush">
                                <?php while ($pago = mysqli_fetch_assoc($historial_result)): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Mesa <?php echo $pago['numero_mesa']; ?></h6>
                                                <p class="small text-muted mb-0">
                                                    <?php echo date('d/m/Y H:i', strtotime($pago['fecha_hora'])); ?>
                                                    (<?php echo $pago['num_productos']; ?> productos)
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <h6 class="mb-0"><?php echo number_format($pago['total'], 2); ?>€</h6>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center text-muted py-3">No hay historial de pagos</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar detalles -->
    <div class="modal fade" id="detalleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detalleBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .hover-effect {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }

    .fas {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.5em;
        height: 1.5em;
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 2rem 1rem;
        }
        
        .fs-1 {
            font-size: 2.5rem !important;
        }
    }

    /* Estilos para el historial */
    .list-group-item {
        transition: background-color 0.2s;
    }

    .list-group-item:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .btn-outline-primary {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    @media (max-width: 576px) {
        .list-group-item {
            padding: 0.75rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
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
