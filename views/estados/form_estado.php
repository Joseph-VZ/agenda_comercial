<form id="form-nuevo-estado" autocomplete="off" method="POST">
    <div class="mb-3">
        <label for="clave" class="form-label">Clave:</label>
        <input type="text" class="form-control" name="clave" placeholder="Ej. 01" maxlength="2" required>
    </div>

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre del Estado:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ej. Aguascalientes" maxlength="40" required>
    </div>

    <input type="hidden" name="abrev" value="<?= htmlspecialchars($estado['abrev']) ?>">

    <button type="submit" id="btn-guardar-estado" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-estado">Cancelar</button>
</form>
