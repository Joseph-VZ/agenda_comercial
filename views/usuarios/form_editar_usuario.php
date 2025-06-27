<?php if ($usuario): ?>
<form id="form-editar-usuario" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" name="nombre" value="<?= $usuario['nombre'] ?>" required>
    </div>

    <div class="mb-3">
        <label for="correo" class="form-label">Correo:</label>
        <input type="email" class="form-control" name="correo" id="correo" value="<?= $usuario['correo'] ?>" required>
        <div id="correo-error" class="invalid-feedback d-block"></div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Nueva Contraseña:</label>
        <input type="password" class="form-control" name="password" placeholder="Déjalo vacío si no deseas cambiarla">
    </div>

    <div class="mb-3">
        <label for="rol" class="form-label">Rol:</label>
        <select class="form-select" name="rol" required>
            <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="gerente" <?= $usuario['rol'] === 'gerente' ? 'selected' : '' ?>>Gerente</option>
            <option value="asesor" <?= $usuario['rol'] === 'asesor' ? 'selected' : '' ?>>Asesor</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="fotografia" class="form-label">Fotografía:</label>
        <input type="file" name="fotografia" id="fotografia" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <?php if (!empty($usuario['fotografia'])): ?>
            <img id="previewFoto" src="uploads/usuarios/<?= htmlspecialchars($usuario['fotografia']) ?>" alt="Vista previa" style="max-width: 100px; max-height: 100px; border-radius: 50%; object-fit: cover;">
        <?php else: ?>
            <img id="previewFoto" src="" alt="Vista previa" style="max-width: 100px; max-height: 100px; display: none; border-radius: 50%; object-fit: cover;">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <button type="button" class="btn btn-secondary" id="btn-cancelar-usuario">Cancelar</button>
</form>
<?php else: ?>
    <p class="text-danger">Usuario no encontrado.</p>
<?php endif; ?>
