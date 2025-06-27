<?php if ($evento): ?>
<form id="form-editar-google">
    <!-- Oculto el ID original del evento de Google (incluye el prefijo "google_") -->
    <!-- ID del evento de Google Calendar -->
    <input type="hidden" name="google_event_id" value="<?= htmlspecialchars($evento['google_event_id']) ?>">

    <!-- Acción esperada por el controlador -->
    <input type="hidden" name="accion" value="guardar_edicion_evento_google">

    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha:</label>
        <input type="date" class="form-control" name="fecha" required value="<?= htmlspecialchars($evento['fecha']) ?>">
    </div>

    <div class="mb-3">
        <label for="hora_inicio" class="form-label">Hora Inicio:</label>
        <input type="time" class="form-control" name="hora_inicio" required value="<?= htmlspecialchars($evento['hora_inicio']) ?>">
    </div>

    <div class="mb-3">
        <label for="hora_fin" class="form-label">Hora Fin:</label>
        <input type="time" class="form-control" name="hora_fin" required value="<?= htmlspecialchars($evento['hora_fin']) ?>">
    </div>

    <div class="mb-3">
        <label for="motivo" class="form-label">Motivo:</label>
        <textarea class="form-control" name="motivo" required><?= htmlspecialchars($evento['motivo']) ?></textarea>
    </div>

    <div class="mb-3">
        <label for="id_cliente" class="form-label">Cliente:</label>
        <select class="form-select" name="id_cliente" required>
            <option value="" disabled>Selecciona un cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <?php if ($cliente['estatus'] == 1): ?>
                    <option value="<?= $cliente['id'] ?>" <?= $evento['id_cliente'] == $cliente['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cliente['nombre']) ?>
                    </option>
                <?php elseif ($evento['id_cliente'] == $cliente['id']): ?>
                    <option value="<?= $cliente['id'] ?>" selected disabled>
                        <?= htmlspecialchars($cliente['nombre']) ?> (Desactivado)
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="id_usuario" class="form-label">Usuario Responsable:</label>
        <select class="form-select" name="id_usuario" required>
            <option value="" disabled>Selecciona un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <?php if ($usuario['estatus'] == 1): ?>
                    <option value="<?= $usuario['id'] ?>" <?= $evento['id_usuario'] == $usuario['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($usuario['nombre']) ?>
                    </option>
                <?php elseif ($evento['id_usuario'] == $usuario['id']): ?>
                    <option value="<?= $usuario['id'] ?>" selected disabled>
                        <?= htmlspecialchars($usuario['nombre']) ?> (Desactivado)
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="estatus" class="form-label">Estatus:</label>
        <select class="form-select" name="estatus" required>
            <option value="1" <?= $evento['estatus'] == 1 ? 'selected' : '' ?>>Confirmada</option>
            <option value="0" <?= $evento['estatus'] == 0 ? 'selected' : '' ?>>Pendiente</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-cita">Cancelar</button>
    <button type="button" class="btn btn-danger btn-eliminar-cita" data-id="<?= htmlspecialchars($evento['google_event_id']) ?>">Eliminar Evento</button>
</form>
<?php else: ?>
    <p class="text-danger">Evento no encontrado.</p>
<?php endif; ?>
