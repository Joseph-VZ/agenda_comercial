<div class="table-responsive">
    <table id="tablaEstados" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Clave</th>
                <th>Nombre</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($estados)): ?>
                <?php foreach ($estados as $estado): ?>
                    <tr>
                        <td><?= $estado['id'] ?></td>
                        <td><?= $estado['clave'] ?></td>
                        <td><?= $estado['nombre'] ?></td>
                        <td>
                            <?php if ($estado['activo'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($estado['activo'] == 1): ?>
                                <button class="btn btn-warning btn-sm btn-editar-estado" data-id="<?= $estado['id'] ?>">Editar ✏️</button>
                                <button class="btn btn-danger btn-sm btn-eliminar-estado" data-id="<?= $estado['id'] ?>">Eliminar 🗑️</button>
                            <?php else: ?>
                                <button class="btn btn-success btn-sm btn-activar-estado" data-id="<?= $estado['id'] ?>">🔓 Activar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No hay estados registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>