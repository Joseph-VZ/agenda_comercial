<form id="form-nuevo-prospecto" autocomplete="off">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Nombre del prospecto" required>
    </div>

    <div class="mb-3">
        <label for="contacto" class="form-label">Contacto:</label>
        <input type="text" class="form-control" name="contacto" placeholder="Información de contacto" required>
    </div>

    <div class="mb-3">
        <label for="estado_interes" class="form-label">Estado:</label>
        <select class="form-control" id="estado_interes" name="estado_interes" required>
            <option value="">Seleccionar estado</option>
            <option value="Nuevo">Nuevo</option>
            <option value="Interesado">Interesado</option>
            <option value="En seguimiento">En seguimiento</option>
            <option value="No interesado">No interesado</option>
        </select>
    </div>



    <button type="submit" class="btn btn-success">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-prospecto">Cancelar</button>
</form>
