<?php if ($prospecto): ?>
<form id="form-editar-prospecto">
    <input type="hidden" name="id" value="<?= $prospecto['id'] ?>">

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" name="nombre" value="<?= $prospecto['nombre'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="contacto" class="form-label">Contacto:</label>
        <input type="text" class="form-control" name="contacto" value="<?= $prospecto['contacto'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="estado_interes" class="form-label">Estado:</label>
        <select class="form-control" id="estado_interes" name="estado_interes" required>
            <option value="">Seleccionar estado</option>
            <option value="Nuevo" <?= $prospecto['estado_interes'] == 'Nuevo' ? 'selected' : '' ?>>Nuevo</option>
            <option value="Interesado" <?= $prospecto['estado_interes'] == 'Interesado' ? 'selected' : '' ?>>Interesado</option>
            <option value="En seguimiento" <?= $prospecto['estado_interes'] == 'En seguimiento' ? 'selected' : '' ?>>En seguimiento</option>
            <option value="No interesado" <?= $prospecto['estado_interes'] == 'No interesado' ? 'selected' : '' ?>>No interesado</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Fecha de registro:</label>
        <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($prospecto['created_at'])) ?>" readonly>
    </div>


    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-prospecto">Cancelar</button>
</form>
<?php else: ?>
    <p class="text-danger">Prospecto no encontrado.</p>
<?php endif; ?>
