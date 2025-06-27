<div class="table-responsive">
    <table id="tablaMunicipios" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Clave</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($municipios)): ?>
                <?php foreach ($municipios as $municipio): ?>
                    <tr>
                        <td><?= $municipio['id'] ?></td>
                        <td><?= $municipio['clave'] ?></td>
                        <td><?= $municipio['nombre'] ?></td>
                        <td><?= $municipio['estado_nombre'] ?? 'Sin estado' ?></td>
                        <td>
                            <?php if ($municipio['activo'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($municipio['activo'] == 1): ?>
                                <button class="btn btn-warning btn-sm btn-editar-municipio" data-id="<?= $municipio['id'] ?>">Editar ✏️</button>
                                <button class="btn btn-danger btn-sm btn-eliminar-municipio" data-id="<?= $municipio['id'] ?>">Eliminar 🗑️</button>
                            <?php else: ?>
                                <button class="btn btn-success btn-sm btn-activar-municipio" data-id="<?= $municipio['id'] ?>">🔓 Activar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay municipios registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
