<form id="form-nueva-marca" autocomplete="off" method="POST">
    <input type="hidden" name="accion" value="guardar_nuevo">

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Marca:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ej. Samsung" maxlength="60" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <textarea class="form-control" name="descripcion" placeholder="Breve descripción de la marca" rows="3" maxlength="255"></textarea>
    </div>

    <button type="submit" id="btn-guardar-marca" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-marca">Cancelar</button>
</form>
