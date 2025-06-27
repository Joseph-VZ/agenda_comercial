<form id="formLocalidad" autocomplete="off" method="POST">
    <div class="mb-3">
        <label for="clave" class="form-label">Clave:</label>
        <input type="text" class="form-control" name="clave" placeholder="Ej. 0001" maxlength="4" required>
    </div>

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Localidad:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ej. Localidad Ejemplo" maxlength="100" required>
    </div>

    <div class="mb-3">
        <label for="municipio_id" class="form-label">Municipio:</label>
        <select class="form-select" name="municipio_id" required>
            <option value="">Seleccione un municipio</option>
            <?php foreach ($municipios as $municipio): ?>
                <option value="<?= $municipio['id'] ?>"><?= htmlspecialchars($municipio['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
    </div>

</form>

