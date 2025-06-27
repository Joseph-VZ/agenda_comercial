<form id="form-nuevo-producto" autocomplete="off" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del producto:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Nombre del producto" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <textarea class="form-control" name="descripcion" placeholder="Breve descripción del producto" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="precio" class="form-label">Precio:</label>
        <input type="number" step="0.01" class="form-control" name="precio" placeholder="0.00" required>
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock:</label>
        <input type="number" class="form-control" name="stock" placeholder="Cantidad en inventario" required>
    </div>
    <div class="mb-3">
        <label for="estatus" class="form-label">Estatus:</label>
        <select class="form-select" name="estatus" required>
            <option value="" selected disabled>Selecciona un estatus</option>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select>
    </div>

    <!-- CAMPO DE FOTOGRAFÍA -->
    <div class="mb-3">
        <label for="fotografia" class="form-label">Fotografía:</label>
        <input type="file" name="fotografia" id="fotografia" class="form-control" accept="image/*">
    </div>

    <!-- PREVISUALIZACIÓN -->
    <div class="mb-3">
        <img id="previewFotoProducto" src="" alt="Vista previa" style="max-width: 100px; max-height: 100px; display: none; border-radius: 8px; object-fit: cover;">
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-producto">Cancelar</button>
</form>

