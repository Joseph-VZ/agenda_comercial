<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>


<form id="form-nueva-cita" autocomplete="off">

    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha:</label>
        <input type="date" class="form-control" name="fecha" required value="<?= $_POST['fecha'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <label for="hora_inicio" class="form-label">Hora de inicio:</label>
        <input type="time" class="form-control" name="hora_inicio" required>
    </div>
    <div class="mb-3">
        <label for="hora_fin" class="form-label">Hora de fin:</label>
        <input type="time" class="form-control" name="hora_fin" required>
    </div>
    <div class="mb-3">
        <label for="motivo" class="form-label">Motivo:</label>
        <textarea class="form-control" name="motivo" placeholder="Ingresa el motivo de la cita" required></textarea>
    </div>
    <div class="mb-3">
        <label for="id_cliente" class="form-label">Cliente:</label>
        <select class="form-select" name="id_cliente" required>
            <option value="" disabled selected>Selecciona un cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <?php if ($cliente['estatus'] == 1): ?>
                    <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="id_usuario" class="form-label">Usuario Responsable:</label>
        <select class="form-select" name="id_usuario" required>
            <option value="" disabled selected>Selecciona un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <?php if ($usuario['estatus'] == 1): ?>
                    <option value="<?= $usuario['id'] ?>"><?= htmlspecialchars($usuario['nombre']) ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="estatus" class="form-label">Estatus:</label>
        <select class="form-select" name="estatus" required>
            <option value="1">Confirmada</option>
            <option value="0">Pendiente</option>
        </select>
    </div>

    <?php if (isset($_SESSION['access_token'])): ?>
        <div class="mb-3">
            <label for="guardar_en" class="form-label">¿Dónde deseas guardar la cita?</label>
            <select class="form-select" name="guardar_en" id="guardar_en" required>
                <option value="local">Solo en el sistema</option>
                <option value="google">Solo en Google Calendar</option>
                <option value="ambos">Guardar en ambos</option>
            </select>
        </div>

    <?php endif; ?>

    <button type="submit" class="btn btn-success">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-cita">Cancelar</button>

</form>
