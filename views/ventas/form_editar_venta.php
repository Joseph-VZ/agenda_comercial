<?php if ($venta): ?>
<form id="form-editar-venta">
    <input type="hidden" name="accion" value="guardar_edicion">
    <input type="hidden" name="id" id="id" value="<?= $venta['id'] ?>">

    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha:</label>
        <input type="date" class="form-control" id="fecha" name="fecha" value="<?= $venta['fecha'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="id_cliente" class="form-label">Cliente:</label>
        <select class="form-select" id="id_cliente" name="id_cliente" required>
            <option value="" disabled>Selecciona un cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id'] ?>" <?= $venta['id_cliente'] == $cliente['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cliente['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="id_usuario" class="form-label">Usuario que realizó la venta:</label>
        <select class="form-select" id="id_usuario" name="id_usuario" required>
            <option value="" disabled>Selecciona un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= $usuario['id'] ?>" <?= $venta['id_usuario'] == $usuario['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($usuario['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <hr>
    

    <!-- Sección para agregar productos -->
    <div class="mb-3">
        <label class="form-label">Modificar productos:</label>
        <div class="row g-2 mb-2">
            <div class="col-md-6">
                <select class="form-select" id="producto-select">
                    <option value="" disabled selected>Selecciona un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['id'] ?>" data-precio="<?= $producto['precio'] ?>">
                            <?= htmlspecialchars($producto['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" id="cantidad-input" placeholder="Cantidad" min="1">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary w-100" id="btn-agregar-producto">Agregar</button>
            </div>
        </div>

        <table class="table table-bordered" id="tabla-productos">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th>Precio unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($venta['productos'] as $detalle): ?>
                    <?php
                        $producto = array_filter($productos, fn($p) => $p['id'] == $detalle['id_producto']);
                        $producto = reset($producto);
                        $nombre = $producto ? $producto['nombre'] : 'Producto desconocido';
                        $precio = isset($detalle['precio_unitario']) ? $detalle['precio_unitario'] : ($producto['precio'] ?? 0);
                        $subtotal = $detalle['cantidad'] * $precio;
                    ?>
                    <tr data-id="<?= $detalle['id_producto'] ?>">
                        <td><?= htmlspecialchars($nombre) ?></td>
                        <td class="precio"><?= number_format($precio, 2) ?></td>
                        <td class="cantidad"><?= $detalle['cantidad'] ?></td>
                        <td class="subtotal"><?= number_format($subtotal, 2) ?></td>
                        <td><button type="button" class="btn btn-danger btn-sm btn-eliminar">Eliminar</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-end fw-bold fs-5">
            Total: $<span id="total-general">0.00</span>
        </div>
    </div>

    <!-- Campo oculto para enviar los productos en formato JSON -->
    <input type="hidden" name="productos" id="productos-json" value='<?= htmlspecialchars($venta['productos_json'], ENT_QUOTES, "UTF-8") ?>'>


    <div class="mt-4">
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <button type="button" class="btn btn-secondary" id="btn-cancelar-venta">Cancelar</button>
    </div>
</form>

<!-- Script para recalcular el total al abrir el formulario -->
<script>
    if (typeof calcularTotal === 'function') {
        calcularTotal();
    }
</script>
<?php else: ?>
<p class="text-danger">Venta no encontrada.</p>
<?php endif; ?>
