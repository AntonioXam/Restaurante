<?php
// Incluir archivos necesarios para la sesión y conexión
include 'sesion_encargado.php';
include '../conexion.php';

// Mostrar formulario inicial si no hay POST
if ($_SERVER['REQUEST_METHOD'] != 'POST'):
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Informe - Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #17a2b8;
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>
                                Generar Informe
                            </h5>
                            <a href="index.php" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio:</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin:</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-info text-white">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Generar Informe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// ...existing code hasta else...
else:
/**
 * Genera informe de ventas para el período especificado
 * Calcula:
 * - Número total de ventas por día
 * - Ingresos totales por día
 * - Totales generales del período
 * 
 * @param string $fecha_inicio Fecha inicial del período
 * @param string $fecha_fin Fecha final del período
 * @return array Resultados agrupados por día
 */
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener fechas del formulario
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        // Consulta para obtener resumen de ventas agrupado por día
        // Ejemplo:
        // SELECT 
        //   DATE(fecha_hora) as fecha,
        //   COUNT(*) as num_ventas,
        //   SUM(subtotal) as total_dia
        // FROM cuentas_pagadas
        // WHERE DATE(fecha_hora) BETWEEN '2023-01-01' AND '2023-12-31'
        // GROUP BY DATE(fecha_hora)
        // ORDER BY fecha
        $query = "SELECT 
            DATE(fecha_hora) as fecha,
            COUNT(*) as num_ventas,
            SUM(subtotal) as total_dia
        FROM cuentas_pagadas
        WHERE DATE(fecha_hora) BETWEEN ? AND ?
        GROUP BY DATE(fecha_hora)
        ORDER BY fecha";

        // Preparar y ejecutar la consulta
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ss", $fecha_inicio, $fecha_fin);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Informe de Ventas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* Estilos específicos para la impresión */
            @media print {
                /* Ocultar elementos que no deben imprimirse */
                .no-print {
                    display: none;
                }
                /* Estilos para la tabla en modo impresión */
                .table {
                    width: 100%;
                    margin-bottom: 1rem;
                    border-collapse: collapse;
                }
                .table th,
                .table td {
                    padding: 0.75rem;
                    border: 1px solid #dee2e6;
                }
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="container py-4">
            <!-- Encabezado del informe -->
            <h2 class="text-center mb-4">Informe de Ventas</h2>
            <h5 class="text-center mb-4">Período: <?php echo date('d/m/Y', strtotime($fecha_inicio)); ?> - <?php echo date('d/m/Y', strtotime($fecha_fin)); ?></h5>
            
            <!-- Tabla de resultados -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Número de Ventas</th>
                            <th>Total del Día</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Inicializar variables para totales
                        $total_ventas = 0;
                        $total_ingresos = 0;
                        
                        // Mostrar datos de cada día y calcular totales
                        while ($row = mysqli_fetch_assoc($result)) {
                            $total_ventas += $row['num_ventas'];
                            $total_ingresos += $row['total_dia'];
                        ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                                <td class="text-center"><?php echo $row['num_ventas']; ?></td>
                                <td class="text-end"><?php echo number_format($row['total_dia'], 2); ?>€</td>
                            </tr>
                        <?php } ?>
                        <!-- Fila de totales -->
                        <tr class="table-primary">
                            <th>TOTALES</th>
                            <th class="text-center"><?php echo $total_ventas; ?></th>
                            <th class="text-end"><?php echo number_format($total_ingresos, 2); ?>€</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Botones de acción (no se imprimen) -->
            <div class="text-center mt-4 no-print">
                <a href="generar_informe.php" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </body>
    </html>
<?php
    }
endif;
?>