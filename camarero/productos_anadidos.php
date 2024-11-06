<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Productos en el Pedido</h5>
                <?php if ($detalle_pedidos_result && mysqli_num_rows($detalle_pedidos_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                            <tbody>
                                <?php 
                                $total = 0;
                                while ($detalle = mysqli_fetch_assoc($detalle_pedidos_result)): 
                                    $subtotal = $detalle['cantidad'] * $detalle['precio'];
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                                    <td><?php echo $detalle['cantidad']; ?></td>
                                    <td><?php echo number_format($detalle['precio'], 2); ?>€</td>
                                    <td><?php echo number_format($subtotal, 2); ?>€</td>
                                    <td><?php echo htmlspecialchars($detalle['notas']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" 
                                                onclick="modificarCantidad(<?php echo $detalle['id']; ?>, 
                                                                         <?php echo $detalle['cantidad']; ?>, 
                                                                         '<?php echo addslashes($detalle['notas']); ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
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
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong><?php echo number_format($total, 2); ?>€</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                        <form action="" method="POST" class="mt-3">
                            <input type="hidden" name="action" value="enviar_cocina">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-utensils"></i> Enviar a Cocina
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No hay productos en el pedido</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
