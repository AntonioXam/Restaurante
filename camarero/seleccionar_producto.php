<section class="p-4 bg-light border rounded shadow-sm">
    <h2>Seleccionar Producto</h2>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pizzas-tab" data-toggle="tab" href="#pizzas" role="tab" aria-controls="pizzas" aria-selected="true">Pizzas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="ensaladas-tab" data-toggle="tab" href="#ensaladas" role="tab" aria-controls="ensaladas" aria-selected="false">Ensaladas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="bebidas-tab" data-toggle="tab" href="#bebidas" role="tab" aria-controls="bebidas" aria-selected="false">Bebidas</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="pizzas" role="tabpanel" aria-labelledby="pizzas-tab">
            <form action="gestionar_pedido.php?mesa_id=<?php echo $mesa_id; ?>" method="post" class="form mt-3">
                <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                <div class="form-group">
                    <label for="producto_id">Producto:</label>
                    <select id="producto_id" name="producto_id" class="form-control">
                        <?php while ($row = mysqli_fetch_assoc($pizzas_result)) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <div class="form-group">
                    <label for="notas">Notas:</label>
                    <textarea class="form-control" id="notas" name="notas"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Añadir</button>
            </form>
        </div>
        <div class="tab-pane fade" id="ensaladas" role="tabpanel" aria-labelledby="ensaladas-tab">
            <form action="gestionar_pedido.php?mesa_id=<?php echo $mesa_id; ?>" method="post" class="form mt-3">
                <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                <div class="form-group">
                    <label for="producto_id">Producto:</label>
                    <select id="producto_id" name="producto_id" class="form-control">
                        <?php while ($row = mysqli_fetch_assoc($ensaladas_result)) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <div class="form-group">
                    <label for="notas">Notas:</label>
                    <textarea class="form-control" id="notas" name="notas"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Añadir</button>
            </form>
        </div>
        <div class="tab-pane fade" id="bebidas" role="tabpanel" aria-labelledby="bebidas-tab">
            <form action="gestionar_pedido.php?mesa_id=<?php echo $mesa_id; ?>" method="post" class="form mt-3">
                <input type="hidden" name="mesa_id" value="<?php echo $mesa_id; ?>">
                <div class="form-group">
                    <label for="producto_id">Producto:</label>
                    <select id="producto_id" name="producto_id" class="form-control">
                        <?php while ($row = mysqli_fetch_assoc($bebidas_result)) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <div class="form-group">
                    <label for="notas">Notas:</label>
                    <textarea class="form-control" id="notas" name="notas"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Añadir</button>
            </form>
        </div>
    </div>
</section>
