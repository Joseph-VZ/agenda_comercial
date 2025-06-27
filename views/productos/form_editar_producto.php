<?php if ($producto): ?>
<form id="form-editar-producto" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $producto['id'] ?>">

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del producto:</label>
        <input type="text" class="form-control" name="nombre" value="<?= $producto['nombre'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <textarea class="form-control" name="descripcion" rows="3" required><?= $producto['descripcion'] ?></textarea>
    </div>

    <div class="mb-3">
        <label for="precio" class="form-label">Precio:</label>
        <input type="number" step="0.01" class="form-control" name="precio" value="<?= $producto['precio'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stock:</label>
        <input type="number" class="form-control" name="stock" value="<?= $producto['stock'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="estatus" class="form-label">Estatus:</label>
        <select class="form-select" name="estatus" required>
            <option value="1" <?= $producto['estatus'] == 1 ? 'selected' : '' ?>>Activo</option>
            <option value="0" <?= $producto['estatus'] == 0 ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="fotografia" class="form-label">Fotografía:</label>
        <input type="file" name="fotografia" id="fotografia" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <?php if (!empty($producto['fotografia'])): ?>
            <img id="previewFotoProducto" src="uploads/productos/<?= htmlspecialchars($producto['fotografia']) ?>" alt="Vista previa" style="max-width: 100px; max-height: 100px; border-radius: 12px; object-fit: cover;">
        <?php else: ?>
            <img id="previewFotoProducto" src="" alt="Vista previa" style="max-width: 100px; max-height: 100px; display: none; border-radius: 12px; object-fit: cover;">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-producto">Cancelar</button>
</form>
<?php else: ?>
    <p class="text-danger">Producto no encontrado.</p>
<?php endif; ?>
