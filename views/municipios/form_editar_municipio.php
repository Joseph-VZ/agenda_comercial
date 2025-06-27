<?php if ($municipio): ?>
    <form id="form-editar-municipio" method="POST">
        <input type="hidden" name="id" value="<?= $municipio['id'] ?>">

        <div class="mb-3">
            <label for="clave" class="form-label">Clave:</label>
            <input type="text" class="form-control" name="clave" value="<?= htmlspecialchars($municipio['clave']) ?>" maxlength="3" required>
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Municipio:</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($municipio['nombre']) ?>" maxlength="60" required>
        </div>

        <div class="mb-3">
            <label for="estado_id" class="form-label">Estado:</label>
            <select class="form-select" name="estado_id" required>
                <option value="">Seleccione un estado</option>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= $estado['id'] ?>" <?= $estado['id'] == $municipio['estado_id'] ? 'selected' : '' ?>>
                        <?= $estado['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-secondary" id="btn-cancelar-municipio">Cancelar</button>
    </form>
<?php else: ?>
    <p class="text-danger">Municipio no encontrado.</p>
<?php endif; ?>
