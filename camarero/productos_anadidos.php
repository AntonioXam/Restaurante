<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Productos en el Pedido</h5>
                <?php if ($detalle_pedidos_result && mysqli_num_rows($detalle_pedidos_result) > 0): ?>
                    <!-- Tabla Responsive -->
                    <div class="table-responsive">
                        <!-- Estructura de la tabla -->
                        <table class="table table-hover">
                            <!-- Encabezados -->
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                    <th>Notas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <!-- Cuerpo de la tabla -->
                            <tbody>
                                <?php 
                                $total = 0; // Inicializar total
                                while ($detalle = mysqli_fetch_assoc($detalle_pedidos_result)): 
                                    // Cálculos para cada producto
                                    $subtotal = $detalle['cantidad'] * $detalle['precio'];
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <!-- Datos del producto -->
                                    <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                                    <td><?php echo $detalle['cantidad']; ?></td>
                                    <td><?php echo number_format($detalle['precio'], 2); ?>€</td>
                                    <td><?php echo number_format($subtotal, 2); ?>€</td>
                                    <td><?php echo htmlspecialchars($detalle['notas']); ?></td>
                                    <!-- Botones de acción -->
                                    <td>
                                        <!-- Botón modificar con modal -->
                                        <button class="btn btn-sm btn-warning" 
                                                onclick="modificarCantidad(<?php echo $detalle['id']; ?>, 
                                                                         <?php echo $detalle['cantidad']; ?>, 
                                                                         '<?php echo addslashes($detalle['notas']); ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Formulario para eliminar -->
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="eliminar">
                                            <input type="hidden" name="detalle_id" value="<?php echo $detalle['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <!-- Pie de tabla con total -->
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong><?php echo number_format($total, 2); ?>€</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                        <!-- Botón enviar a cocina -->
                        <form action="" method="POST" class="mt-3">
                            <input type="hidden" name="action" value="enviar_cocina">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-utensils"></i> Enviar a Cocina
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Mensaje cuando no hay productos -->
                    <p class="text-muted">No hay productos en el pedido</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// ...existing code...

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'enviar_cocina') {
    // Iniciar transacción
    mysqli_begin_transaction($conexion);
    
    try {
        // Obtener todos los detalles del pedido actual
        $query = "SELECT dp.*, p.nombre as nombre_producto, p.precio 
                 FROM detalle_pedidos dp 
                 INNER JOIN productos p ON dp.producto_id = p.id 
                 INNER JOIN pedidos ped ON dp.pedido_id = ped.id 
                 WHERE ped.mesa_id = ? AND ped.estado = 'pendiente'";
        
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "i", $mesa_id);
        mysqli_stmt_execute($stmt);
        $detalles = mysqli_stmt_get_result($stmt);

        // Guardar cada producto en el historial y en la cuenta
        while ($detalle = mysqli_fetch_assoc($detalles)) {
            $subtotal = $detalle['cantidad'] * $detalle['precio'];
            
            // Insertar en historial_pedidos
            $insert_historial = "INSERT INTO historial_pedidos 
                               (mesa_id, producto_id, cantidad, precio_unitario, subtotal, notas) 
                               VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt_historial = mysqli_prepare($conexion, $insert_historial);
            mysqli_stmt_bind_param($stmt_historial, "iiidds", 
                $mesa_id, 
                $detalle['producto_id'],
                $detalle['cantidad'],
                $detalle['precio'],
                $subtotal,
                $detalle['notas']
            );
            mysqli_stmt_execute($stmt_historial);

            // Insertar en cuenta
            $insert_cuenta = "INSERT INTO cuenta 
                            (mesa_id, producto_id, cantidad, precio_unitario, subtotal) 
                            VALUES (?, ?, ?, ?, ?)";
            
            $stmt_cuenta = mysqli_prepare($conexion, $insert_cuenta);
            mysqli_stmt_bind_param($stmt_cuenta, "iiidd",
                $mesa_id,
                $detalle['producto_id'],
                $detalle['cantidad'],
                $detalle['precio'],
                $subtotal
            );
            mysqli_stmt_execute($stmt_cuenta);

            // Insertar en temp_ticket para el PDF
            $insert_ticket = "INSERT INTO temp_ticket 
                            (mesa_id, producto, cantidad, precio_unitario, subtotal) 
                            VALUES (?, ?, ?, ?, ?)";
            
            $stmt_ticket = mysqli_prepare($conexion, $insert_ticket);
            mysqli_stmt_bind_param($stmt_ticket, "isidd",
                $mesa_id,
                $detalle['nombre_producto'],
                $detalle['cantidad'],
                $detalle['precio'],
                $subtotal
            );
            mysqli_stmt_execute($stmt_ticket);
        }

        // Actualizar el estado del pedido a 'enviado'
        mysqli_query($conexion, "UPDATE pedidos SET estado = 'enviado' WHERE mesa_id = $mesa_id AND estado = 'pendiente'");
        
        // Confirmar transacción
        mysqli_commit($conexion);
        
        header("Location: gestionar_pedido.php?mesa_id=$mesa_id&status=success");
        exit;
        
    } catch (Exception $e) {
        // Revertir cambios si hay error
        mysqli_rollback($conexion);
        header("Location: gestionar_pedido.php?mesa_id=$mesa_id&status=error");
        exit;
    }
}

// ...existing code...
?>

<!-- ======== JAVASCRIPT ======== -->
<script>
/**
 * Función para modificar la cantidad y notas de un producto
 * @param {number} detalle_id - ID del detalle del pedido a modificar
 * @param {number} cantidad - Cantidad actual del producto
 * @param {string} notas - Notas actuales del producto
 */
function modificarCantidad(detalle_id, cantidad, notas) {
    // Llenar el modal con los datos actuales
    document.getElementById('mod_detalle_id').value = detalle_id;
    document.getElementById('mod_cantidad').value = cantidad;
    document.getElementById('mod_notas').value = notas;
    // Mostrar el modal
    new bootstrap.Modal(document.getElementById('modificarModal')).show();
}
</script>

<!-- ======== ESTILOS CSS ======== -->
<style>
/* Estilos para las tarjetas de productos */
.card {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
