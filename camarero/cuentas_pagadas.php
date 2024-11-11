<?php
include '../sesion.php';
include '../conexion.php';

// Consulta para obtener cuentas agrupadas
$query = "SELECT 
            cp.mesa_id,
            m.numero_mesa,
            DATE(cp.fecha_hora) as fecha,
            TIME(cp.fecha_hora) as hora,
            COUNT(*) as num_productos,
            SUM(cp.subtotal) as total
          FROM cuentas_pagadas cp
          INNER JOIN mesas m ON cp.mesa_id = m.id
          GROUP BY cp.mesa_id, DATE(cp.fecha_hora), TIME(cp.fecha_hora)
          ORDER BY cp.fecha_hora DESC";

$cuentas_result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Cuentas - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
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

    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-light py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historial de Cuentas Pagadas
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($cuentas_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mobile-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Mesa</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Productos</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($cuenta = mysqli_fetch_assoc($cuentas_result)): ?>
                                    <tr class="cuenta-row">
                                        <td class="mesa-cell" data-label="Mesa">
                                            <strong>Mesa <?php echo $cuenta['numero_mesa']; ?></strong>
                                        </td>
                                        <td class="fecha-cell" data-label="Fecha">
                                            <?php echo date('d/m/Y', strtotime($cuenta['fecha'])); ?>
                                        </td>
                                        <td class="hora-cell" data-label="Hora">
                                            <?php echo date('H:i', strtotime($cuenta['hora'])); ?>
                                        </td>
                                        <td class="productos-cell" data-label="Productos">
                                            <span class="badge bg-secondary">
                                                <?php echo $cuenta['num_productos']; ?> productos
                                            </span>
                                        </td>
                                        <td class="total-cell text-end" data-label="Total">
                                            <strong><?php echo number_format($cuenta['total'], 2); ?>€</strong>
                                        </td>
                                        <td class="action-cell text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="verDetalle('<?php echo $cuenta['mesa_id']; ?>', 
                                                                          '<?php echo $cuenta['fecha']; ?>', 
                                                                          '<?php echo $cuenta['hora']; ?>')">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="d-none d-sm-inline ms-1">Ver Detalle</span>
                                                </button>
                                                <a href="reimprimir_ticket.php?mesa_id=<?php echo $cuenta['mesa_id']; ?>&fecha=<?php echo $cuenta['fecha']; ?>&hora=<?php echo $cuenta['hora']; ?>" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-print"></i>
                                                    <span class="d-none d-sm-inline ms-1">Reimprimir</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table> <!-- Añadir cierre de tabla que faltaba -->
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay historial de cuentas pagadas</p>
                    </div>
                <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function verDetalle(mesaId, fecha, hora) {
        fetch(`obtener_detalle_cuenta.php?mesa_id=${mesaId}&fecha=${fecha}&hora=${hora}`)
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

                html += `
                <tr class="table-light">
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td class="text-end"><strong>${total.toFixed(2)}€</strong></td>
                </tr>`;

                document.getElementById('detalleBody').innerHTML = html;
                new bootstrap.Modal(document.getElementById('detalleModal')).show();
            });
    }
    </script>
    <style>
    /* Estilos generales */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    /* Estilos para la tabla responsive */
    @media (max-width: 768px) {
        .mobile-table thead {
            display: none;
        }

        .mobile-table tbody tr {
            display: flex;
            flex-wrap: wrap;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 0.5rem;
        }

        .mobile-table td {
            border: none;
            padding: 0.25rem 0.5rem;
        }

        .mobile-table td::before {
            content: attr(data-label);
            font-weight: bold;
            display: inline-block;
            width: 100%;
            margin-bottom: 0.25rem;
        }

        .mesa-cell {
            width: 50%;
        }

        .fecha-cell {
            width: 50%;
            text-align: right;
        }

        .hora-cell {
            width: 50%;
        }

        .productos-cell {
            width: 50%;
            text-align: right;
        }

        .total-cell {
            width: 100%;
            color: #0d6efd;
            font-size: 1.1rem;
            padding-top: 0.5rem !important;
            margin-top: 0.5rem;
            border-top: 1px dashed #dee2e6;
        }

        .action-cell {
            width: 100%;
            text-align: right !important;
            padding-top: 0.5rem !important;
        }
    }

    /* Mejoras visuales */
    .badge {
        font-weight: normal;
        padding: 0.5em 0.8em;
    }

    .btn-outline-primary {
        border-radius: 2rem;
        padding: 0.375rem 1rem;
    }

    /* Ajustes para pantallas muy pequeñas */
    @media (max-width: 576px) {
        .mobile-table tr {
            margin-bottom: 0.5rem;
        }

        .btn-sm {
            width: 100%;
            margin-top: 0.5rem;
        }

        .hora-cell, .productos-cell {
            font-size: 0.9rem;
        }
    }

    /* Ajustes para el modal */
    .modal-content {
        border-radius: 0.5rem;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    @media (max-width: 576px) {
        .modal-dialog {
            margin: 0.5rem;
        }
    }
    </style>
</body>
</html>