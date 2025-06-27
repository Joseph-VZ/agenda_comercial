<form id="form-nueva-venta" autocomplete="off">
    <input type="hidden" name="accion" value="guardar_nuevo">

    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha:</label>
        <input type="date" class="form-control" name="fecha" required>
    </div>

    <div class="mb-3">
        <label for="id_cliente" class="form-label">Cliente:</label>
        <select class="form-select" id="id_cliente" name="id_cliente" required>
            <option value="" disabled selected>Selecciona un cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?></option>
            <?php endforeach; ?>
            <option value="nuevo_cliente">+ Nuevo cliente...</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="id_usuario" class="form-label">Usuario que realizó la venta:</label>
        <select class="form-select" id="id_usuario" name="id_usuario" required>
            <option value="" disabled selected>Selecciona un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= $usuario['id'] ?>"><?= htmlspecialchars($usuario['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <hr>

    <!-- Sección para agregar productos -->
    <div class="mb-3">
        <label class="form-label">Agregar productos:</label>
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
            <tbody></tbody>
        </table>

        <div class="text-end fw-bold fs-5">
            Total: $<span id="total-general">0.00</span>
        </div>
    </div>


    <input type="hidden" name="productos" id="productos-json">

    <div class="mt-4">
        <button type="submit" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" id="btn-cancelar-venta">Cancelar</button>
    </div>
</form>


<div class="modal fade" id="modal-nuevo-cliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Aquí se inyectará el formulario vía AJAX -->
    </div>
  </div>
</div>
