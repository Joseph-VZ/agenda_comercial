<form id="form-nueva-categoria" autocomplete="off" method="POST">
    <input type="hidden" name="accion" value="guardar_nuevo">

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la Categoría:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ej. Laptops" maxlength="60" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <textarea class="form-control" name="descripcion" placeholder="Breve descripción de la categoría" rows="3" maxlength="255"></textarea>
    </div>

    <button type="submit" id="btn-guardar-categoria" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-categoria">Cancelar</button>
</form>
