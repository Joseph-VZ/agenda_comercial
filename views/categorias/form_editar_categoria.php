<?php if ($categoria): ?>
    <form id="form-editar-categoria" method="POST">
        <input type="hidden" name="id" value="<?= $categoria['id'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría:</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($categoria['nombre']) ?>" maxlength="60" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" name="descripcion" rows="3" maxlength="255"><?= htmlspecialchars($categoria['descripcion']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-secondary" id="btn-cancelar-categoria">Cancelar</button>
    </form>
<?php else: ?>
    <p class="text-danger">Categoría no encontrada.</p>
<?php endif; ?>
