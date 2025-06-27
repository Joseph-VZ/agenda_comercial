<table id="tablaClientes" class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr class="text-center">
            <th>ID</th>
            <th>Nombre</th>
            <th>Contacto</th>
            <th>Dirección</th>
            <th>Usuario Responsable</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($clientes) && is_array($clientes)): ?>
            <?php foreach ($clientes as $cliente): ?>
                <tr class="text-center">
                    <td><?= htmlspecialchars($cliente['id'] ?? '—') ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($cliente['nombre'] ?? 'Sin nombre') ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($cliente['contacto'] ?? 'Sin contacto') ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($cliente['direccion'] ?? 'Sin dirección') ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($cliente['nombre_usuario'] ?? 'Sin asignar') ?></td>
                    <td>
                        <?php if ($cliente['estatus'] == 1): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-nowrap">
                        <?php if ($cliente['estatus'] == 1): ?>
                            <button class="btn btn-warning btn-sm btn-editar-cliente" data-id="<?= $cliente['id'] ?>">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm btn-desactivar-cliente" data-id="<?= $cliente['id'] ?>">Desactivar</button>
                        <?php else: ?>
                            <button class="btn btn-success btn-sm btn-activar-cliente" data-id="<?= $cliente['id'] ?>">🔓 Activar</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No hay clientes registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
