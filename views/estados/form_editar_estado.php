<?php if ($estado): ?>
<form id="form-editar-estado" method="POST">
    <input type="hidden" name="id" value="<?= $estado['id'] ?>">

    <div class="mb-3">
        <label for="clave" class="form-label">Clave:</label>
        <input type="text" class="form-control" name="clave" value="<?= htmlspecialchars($estado['clave']) ?>" maxlength="2" required>
    </div>

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Estado:</label>
        <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($estado['nombre']) ?>" maxlength="40" required>
    </div>

    <input type="hidden" name="abrev" value="<?= htmlspecialchars($estado['abrev']) ?>">

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-estado">Cancelar</button>
</form>
<?php else: ?>
    <p class="text-danger">Estado no encontrado.</p>
<?php endif; ?>
