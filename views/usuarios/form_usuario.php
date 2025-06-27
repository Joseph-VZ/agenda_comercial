<form id="form-nuevo-usuario" autocomplete="off" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" name="nombre" placeholder="Ingresa el nombre completo" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label for="correo" class="form-label">Correo:</label>
        <input type="email" class="form-control" name="correo" id="correo" placeholder="ejemplo@correo.com" required autocomplete="off">
        <div id="correo-error" class="invalid-feedback d-block"></div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña:</label>
        <input type="password" class="form-control" name="password" placeholder="Contraseña segura" required autocomplete="new-password">
    </div>
    <div class="mb-3">
        <label for="rol" class="form-label">Rol:</label>
        <select class="form-select" name="rol" required>
            <option value="" selected disabled>Selecciona un rol</option>
            <option value="admin">Admin</option>
            <option value="gerente">Gerente</option>
            <option value="asesor">Asesor</option>
        </select>
    </div>
    <div class="mb-3">
    <div class="mb-3">
        <label for="fotografia" class="form-label">Fotografía:</label>
        <input type="file" name="fotografia" id="fotografia" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <img id="previewFoto" src="" alt="Vista previa" style="max-width: 100px; max-height: 100px; display: none; border-radius: 50%; object-fit: cover;">
    </div>
    <button type="submit" id="btn-guardar-usuario" class="btn btn-primary">Guardar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-usuario">Cancelar</button>
</form>

