<div class="table-responsive">
    <table id="tablaZonas" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($zonas)): ?>
                <?php foreach ($zonas as $zon): ?>
                    <tr>
                        <td><?= $zon['id'] ?></td>
                        <td><?= htmlspecialchars($zon['nombre']) ?></td>
                        <td><?= htmlspecialchars($zon['descripcion']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-editar-zona" data-id="<?= $zon['id'] ?>">Editar ✏️</button>
                            <button class="btn btn-danger btn-sm btn-eliminar-zona" data-id="<?= $zon['id'] ?>">Eliminar 🗑️</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No hay Zonas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>