<table id="tablaUsuarios" class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr class="text-center">
            <th>ID</th>
            <th>Foto</th> 
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $usuario): ?>
                <tr class="text-center">
                    <td><?= $usuario['id'] ?></td>
                    <td>
                        <?php if (!empty($usuario['fotografia'])): ?>
                            <img src="../uploads/usuarios/<?= $usuario['fotografia'] ?>" width="40" class="img-fluid img-thumbnail rounded-circle">
                        <?php else: ?>
                            <img src="../assets/img/sinfoto.png" width="40" class="img-fluid img-thumbnail rounded-circle">
                        <?php endif; ?>
                    </td>

                    <td class="text-nowrap"><?= $usuario['nombre'] ?></td>
                    <td class="text-nowrap"><?= $usuario['correo'] ?></td>
                    <td><?= $usuario['rol'] ?></td>

                    <td>
                        <?php if ($usuario['estatus'] == 1): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactivo</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-nowrap">
                        <?php if ($usuario['estatus'] == 1): ?>
                            <button class="btn btn-warning btn-sm btn-editar-usuario" data-id="<?= $usuario['id'] ?>">✏️ Editar</button>
                            <button class="btn btn-danger btn-sm btn-desactivar-usuario" data-id="<?= $usuario['id'] ?>"> Desactivar</button>
                        <?php else: ?>
                            <button class="btn btn-success btn-sm btn-activar-usuario" data-id="<?= $usuario['id'] ?>">🔓 Activar</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No hay usuarios registrados.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
