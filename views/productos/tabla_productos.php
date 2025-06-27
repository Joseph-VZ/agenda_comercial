<div class="table-responsive">
    <table id="tablaProductos" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Foto</th> <!-- NUEVA COLUMNA -->
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= $producto['id'] ?></td>

                    <!-- FOTO DEL PRODUCTO -->
                    <td>
                        <?php if (!empty($producto['fotografia'])): ?>
                            <img src="../uploads/productos/<?= htmlspecialchars($producto['fotografia']) ?>" width="40">
                        <?php else: ?>
                            <img src="../assets/img/sinfoto.png" width="40">
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                    <td><?= $producto['stock'] ?></td>
                    <td>
                        <?php if ($producto['estatus']): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($producto['estatus'] == 1): ?>
                            <button class="btn btn-sm btn-warning btn-editar-producto" data-id="<?= $producto['id'] ?>">Editar</button>
                            <button class="btn btn-sm btn-danger btn-desactivar-producto" data-id="<?= $producto['id'] ?>">Desactivar</button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-success btn-activar-producto" data-id="<?= $producto['id'] ?>">Activar</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" class="text-center">No hay productos registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>