<?php if ($zona): ?>
    <form id="form-editar-zona" method="POST">
        <input type="hidden" name="id" value="<?= $zona['id'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Zona:</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($zona['nombre']) ?>" maxlength="60" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea class="form-control" name="descripcion" rows="3" maxlength="255"><?= htmlspecialchars($zona['descripcion']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-secondary" id="btn-cancelar-zona">Cancelar</button>
    </form>
<?php else: ?>
    <p class="text-danger">Zona no encontrada.</p>
<?php endif; ?>
