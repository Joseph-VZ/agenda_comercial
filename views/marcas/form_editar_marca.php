<?php if ($marca): ?>
    <form id="form-editar-marca" method="POST">
        <input type="hidden" name="id" value="<?= $marca['id'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Marca:</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($marca['nombre']) ?>" maxlength="60" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" name="descripcion" rows="3" maxlength="255"><?= htmlspecialchars($marca['descripcion']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-secondary" id="btn-cancelar-marca">Cancelar</button>
    </form>
<?php else: ?>
    <p class="text-danger">Marca no encontrada.</p>
<?php endif; ?>
