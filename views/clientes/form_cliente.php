<form id="form-nuevo-cliente" autocomplete="off" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre del cliente" required autocomplete="off">
    </div>

    <div class="mb-3">
        <label for="contacto" class="form-label">Contacto:</label>
        <input type="text" class="form-control" name="contacto" placeholder="Ejemplo: (123) 456-7890" required autocomplete="off">
    </div>

    <div class="mb-3">
        <label for="correo" class="form-label">Correo electrónico:</label>
        <input type="email" class="form-control" name="correo" id="correo" placeholder="cliente@ejemplo.com" required autocomplete="off">
        <div id="correo-error" class="invalid-feedback d-block"></div>
    </div>

    <div class="mb-3">
        <label for="id_usuario" class="form-label">Usuario Responsable:</label>
        <select class="form-select" name="id_usuario" required>
            <option value="" disabled selected>Selecciona un usuario</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= $usuario['id'] ?>"><?= htmlspecialchars($usuario['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-cliente">Cancelar</button>
</form>
