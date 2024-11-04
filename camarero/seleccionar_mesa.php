<section class="p-4 bg-light border rounded shadow-sm">
    <h2>Seleccionar Mesa Activa</h2>
    <form action="" method="get" class="form-inline">
        <label for="mesa_id" class="mr-2">Mesa:</label>
        <select id="mesa_id" name="mesa_id" class="form-control mr-2" onchange="this.form.submit()">
            <option value="">Seleccione una mesa</option>
            <?php while ($mesa = mysqli_fetch_assoc($mesas_activas_result)) { ?>
            <option value="<?php echo $mesa['id']; ?>">Mesa <?php echo $mesa['numero_mesa']; ?></option>
            <?php } ?>
        </select>
    </form>
</section>
