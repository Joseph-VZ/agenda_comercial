<div class="table-responsive">
    <table id="tablaMarcas" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($marcas)): ?>
                <?php foreach ($marcas as $marca): ?>
                    <tr>
                        <td><?= $marca['id'] ?></td>
                        <td><?= htmlspecialchars($marca['nombre']) ?></td>
                        <td><?= htmlspecialchars($marca['descripcion']) ?></td>
                        <td>
                            <?php if ($marca['activo'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($marca['activo'] == 1): ?>
                                <button class="btn btn-warning btn-sm btn-editar-marca" data-id="<?= $marca['id'] ?>">Editar ✏️</button>
                                <button class="btn btn-danger btn-sm btn-eliminar-marca" data-id="<?= $marca['id'] ?>">Eliminar 🗑️</button>
                            <?php else: ?>
                                <button class="btn btn-success btn-sm btn-activar-marca" data-id="<?= $marca['id'] ?>">🔓 Activar</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No hay marcas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>