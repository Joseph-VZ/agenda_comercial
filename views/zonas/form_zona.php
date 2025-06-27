<form id="form-nueva-zona" autocomplete="off" method="POST">
    <input type="hidden" name="accion" value="guardar_nuevo">

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Zona:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ej. zona 1" maxlength="60" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <textarea class="form-control" name="descripcion" placeholder="Breve descripción de la zona" rows="3" maxlength="255"></textarea>
    </div>

    <button type="submit" id="btn-guardar-zona" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-zona">Cancelar</button>
</form>
