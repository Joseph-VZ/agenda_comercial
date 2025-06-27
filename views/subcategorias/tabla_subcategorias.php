<div class="table-responsive">
    <table id="tablaSubcategorias" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($subcategorias)): ?>
                <?php foreach ($subcategorias as $subcategoria): ?>
                    <tr>
                        <td><?= $subcategoria['id'] ?></td>
                        <td><?= htmlspecialchars($subcategoria['nombre']) ?></td>
                        <td><?= htmlspecialchars($subcategoria['descripcion']) ?></td>
                        <td><?= $subcategoria['categoria_nombre'] ?? 'Sin categoría' ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-editar-subcategoria" data-id="<?= $subcategoria['id'] ?>">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm btn-eliminar-subcategoria" data-id="<?= $subcategoria['id'] ?>">🗑️ Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No hay subcategorías registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
