<?php
// Incluir archivos de sesión y conexión
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
    <!-- Metadatos y enlaces a estilos externos -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Cuentas - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
    /* Variables de colores */
    :root {
        --restaurant-primary: #2c3e50;    /* Azul oscuro principal */
        --restaurant-secondary: #34495e;   /* Azul oscuro secundario */
        --restaurant-accent: #3498db;      /* Azul claro para acentos */
        --restaurant-light: #ecf0f1;       /* Gris muy claro para fondos */
        --restaurant-dark: #1a252f;        /* Azul muy oscuro */
    }

    /* Estilos generales */
    body {
        background-color: var(--restaurant-light);
    }

    /* Navegación y Cards */
    .navbar {
        background: var(--restaurant-dark) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        background: var(--restaurant-primary) !important;
        color: white;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
    }

    /* Botones y acciones */
    .btn-action {
        background-color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background-color: var(--restaurant-secondary);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-action-btn {
        border-color: white;
        color: white;
    }

    .header-action-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* Tabla y elementos */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(44, 62, 80, 0.05);
        transform: scale(1.01);
    }

    .badge {
        background-color: var(--restaurant-accent) !important;
        padding: 0.5em 1em;
        border-radius: 4px;
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

    /* Estilos para los botones de acción */
    .action-cell .btn-group {
        display: flex;
        gap: 0.25rem;
    }

    @media (max-width: 576px) {
        .action-cell .btn-group {
            flex-direction: column;
            width: 100%;
        }
        
        .action-cell .btn {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        
        .action-cell {
            padding-top: 0.5rem !important;
        }
    }

    @media (max-width: 576px) {
        .modal-dialog {
            margin: 0.5rem;
        }
    }

    /* Nuevos estilos para los botones responsivos */
    .mobile-actions {
        display: flex !important;
    }

    .mobile-actions .btn {
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        padding: 0.5rem;
        margin-bottom: 0.25rem;
        border-radius: 0.25rem;
    }

    .mobile-actions .btn i {
        margin-right: 0.5rem;
        width: 20px;
        text-align: center;
    }

    @media (min-width: 768px) {
        .mobile-actions {
            flex-direction: row !important;
            gap: 0.25rem;
        }

        .mobile-actions .btn {
            width: auto;
            margin-bottom: 0;
        }
    }

    @media (max-width: 767px) {
        .action-cell {
            padding: 1rem !important;
        }

        .btn-group-vertical {
            width: 100%;
        }

        .mobile-actions .btn {
            font-size: 1rem;
            padding: 0.625rem;
        }

        .btn-text {
            font-size: 0.9rem;
        }
    }
    
    /* Ajuste para el contenedor de acciones */
    .action-cell {
        background-color: rgba(0,0,0,0.02);
        border-radius: 0.5rem;
    }
    </style>
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

    <!-- Contenedor principal -->
    <div class="container py-4">
        <div class="card">
            <!-- Encabezado de la tarjeta -->
            <div class="card-header bg-light py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historial de Cuentas Pagadas
                    </h5>
                </div>
            </div>
            <!-- Cuerpo de la tarjeta -->
            <div class="card-body">
                <?php if (mysqli_num_rows($cuentas_result) > 0): ?>
                    <div class="table-responsive">
                        <!-- Tabla de cuentas pagadas -->
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
                                            <div class="btn-group-vertical w-100 mobile-actions">
                                                <button class="btn btn-sm btn-outline-primary mb-1" 
                                                        onclick="verDetalle('<?php echo $cuenta['mesa_id']; ?>', 
                                                                          '<?php echo $cuenta['fecha']; ?>', 
                                                                          '<?php echo $cuenta['hora']; ?>')">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="btn-text">Ver Detalle</span>
                                                </button>
                                                <a href="reimprimir_ticket.php?mesa_id=<?php echo $cuenta['mesa_id']; ?>&fecha=<?php echo $cuenta['fecha']; ?>&hora=<?php echo $cuenta['hora']; ?>" 
                                                   class="btn btn-sm btn-outline-success mb-1">
                                                    <i class="fas fa-print"></i>
                                                    <span class="btn-text">Reimprimir</span>
                                                </a>
                                                <a href="descargar_ticket_pdf.php?mesa_id=<?php echo $cuenta['mesa_id']; ?>&fecha=<?php echo $cuenta['fecha']; ?>&hora=<?php echo $cuenta['hora']; ?>" 
                                                   class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-file-pdf"></i>
                                                    <span class="btn-text">PDF</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table> <!-- Añadir cierre de tabla que faltaba -->
                    </div>
                <?php else: ?>
                    <!-- Mensaje cuando no hay cuentas pagadas -->
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
                <!-- Encabezado del modal -->
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Cuenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Cuerpo del modal -->
                <div class="modal-body">
                    <div class="table-responsive">
                        <!-- Tabla de detalles -->
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

    <!-- Scripts de Bootstrap y funcionalidad -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Función para ver detalles de la cuenta
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
</body>
</html>