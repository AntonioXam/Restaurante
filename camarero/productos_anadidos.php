

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

// ======== JAVASCRIPT ========
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

// ======== ESTILOS CSS ========
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
