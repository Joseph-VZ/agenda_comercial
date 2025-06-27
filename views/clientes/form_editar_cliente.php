<?php if ($cliente): ?>
<form id="form-editar-cliente">
    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" name="nombre" value="<?= $cliente['nombre'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="contacto" class="form-label">Contacto:</label>
        <input type="text" class="form-control" name="contacto" value="<?= $cliente['contacto'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección:</label>
        <input type="text" class="form-control" name="direccion" value="<?= $cliente['direccion'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="id_usuario" class="form-label">Usuario Responsable:</label>
        <select class="form-select" name="id_usuario" required>
            <option value="" disabled>Selecciona un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= $usuario['id'] ?>" <?= $cliente['id_usuario'] == $usuario['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($usuario['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-cliente">Cancelar</button>
</form>
<?php else: ?>
    <p class="text-danger">Cliente no encontrado.</p>
<?php endif; ?>
