<div class="table-responsive">
    <table id="tablaCategorias" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td><?= htmlspecialchars($cat['nombre']) ?></td>
                        <td><?= htmlspecialchars($cat['descripcion']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-editar-categoria" data-id="<?= $cat['id'] ?>">Editar ✏️</button>
                            <button class="btn btn-danger btn-sm btn-eliminar-categoria" data-id="<?= $cat['id'] ?>">Eliminar 🗑️</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No hay categorías registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>