<section class="mt-4 p-4 bg-light border rounded shadow-sm">
    <h2>Productos Añadidos</h2>
    <ul class="list-group">
        <?php if ($detalle_pedidos_result && mysqli_num_rows($detalle_pedidos_result) > 0) { ?>
            <?php while ($detalle = mysqli_fetch_assoc($detalle_pedidos_result)) { ?>
            <li class="list-group-item">
                <?php echo $detalle['cantidad']; ?> x <?php echo $detalle['nombre']; ?> - <?php echo number_format($detalle['precio'], 2); ?> €
            </li>
            <?php } ?>
        <?php } else { ?>
            <li class="list-group-item">No hay productos añadidos.</li>
        <?php } ?>
    </ul>
</section>
