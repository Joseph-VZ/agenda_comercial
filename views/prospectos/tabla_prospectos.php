<?php
function formatearFechaEspanol($fechaISO) {
    $meses = [
        '01' => 'enero', '02' => 'febrero', '03' => 'marzo',
        '04' => 'abril', '05' => 'mayo', '06' => 'junio',
        '07' => 'julio', '08' => 'agosto', '09' => 'septiembre',
        '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'
    ];
    // Extraer solo la fecha sin la hora
    $fechaSolo = explode(' ', $fechaISO)[0]; // toma '2025-05-13' de '2025-05-13 19:52:12'
    $fecha = explode('-', $fechaSolo); 
    return ltrim($fecha[2], '0') . ' de ' . $meses[$fecha[1]] . ' de ' . $fecha[0];
}
?>
<div class="table-responsive">
    <table id="tabla-prospectos" class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Estado de Interés</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($prospectos)): ?>
                <?php foreach ($prospectos as $prospecto): ?>
                    <tr>
                        <td><?= $prospecto['id'] ?></td>
                        <td><?= htmlspecialchars($prospecto['nombre']) ?></td>
                        <td><?= htmlspecialchars($prospecto['contacto']) ?></td>
                        <td><?= htmlspecialchars($prospecto['estado_interes']) ?></td>
                        <td><?= formatearFechaEspanol($prospecto['created_at']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-editar-prospecto" data-id="<?= $prospecto['id'] ?>">
                                ✏️ Editar
                            </button>
                            <button class="btn btn-danger btn-sm btn-eliminar-prospecto" data-id="<?= $prospecto['id'] ?>">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay prospectos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
