<form id="form-nuevo-municipio" autocomplete="off" method="POST">
    <div class="mb-3">
        <label for="clave" class="form-label">Clave:</label>
        <input type="text" class="form-control" name="clave" placeholder="Ej. 001" maxlength="3" required>
    </div>

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Municipio:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ej. Calvillo" maxlength="60" required>
    </div>

    <div class="mb-3">
        <label for="estado_id" class="form-label">Estado:</label>
        <select class="form-select" name="estado_id" required>
            <option value="">Seleccione un estado</option>
            <?php foreach ($estados as $estado): ?>
                <option value="<?= $estado['id'] ?>"><?= $estado['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" id="btn-guardar-municipio" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-municipio">Cancelar</button>
</form>
