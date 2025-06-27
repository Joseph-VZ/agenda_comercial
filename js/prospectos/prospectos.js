$(document).ready(function () {
    function inicializarTablaProspectos() {
        if ($.fn.DataTable.isDataTable('#tabla-prospectos')) {
            $('#tabla-prospectos').DataTable().destroy();
        }

        setTimeout(() => {
            $('#tabla-prospectos').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                },
                dom: 'Bfrtip',
                buttons: ['excelHtml5', 'pdfHtml5', 'print']
            });
        }, 100);
    }

    function recargarTablaProspectos() {
        $.post('../controllers/ProspectoController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-prospecto').html(html);
            inicializarTablaProspectos();
        });
    }

    if ($('#tabla-prospectos').length) {
        inicializarTablaProspectos();
    }

    // NUEVO PROSPECTO
    $(document).off('click', '#btn-nuevo-prospecto').on('click', '#btn-nuevo-prospecto', function () {
        $.post('../controllers/ProspectoController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-prospecto').off().html(response).show();
            $('#contenedor-tabla-prospecto').hide();
        });
    });

    // EDITAR PROSPECTO
    $(document).off('click', '.btn-editar-prospecto').on('click', '.btn-editar-prospecto', function () {
        const id = $(this).data('id');
        $.post('../controllers/ProspectoController.php', { accion: 'formulario_editar', id: id }, function (response) {
            $('#formulario-prospecto').off().html(response).show();
            $('#contenedor-tabla-prospecto').hide();
        });
    });

    // GUARDAR NUEVO PROSPECTO
    $(document).off('submit', '#form-nuevo-prospecto').on('submit', '#form-nuevo-prospecto', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $form.find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        $.post('../controllers/ProspectoController.php', $form.serialize() + '&accion=guardar_nuevo', function (res) {
            $btn.prop('disabled', false);
            if (res.trim() === 'ok') {
                Swal.fire('¡Éxito!', 'Prospecto agregado correctamente. ✅', 'success');
                $('#formulario-prospecto').off().html('').hide();
                $('#contenedor-tabla-prospecto').show();
                recargarTablaProspectos();
            } else {
                Swal.fire('Error', 'No se pudo guardar el prospecto. ❌', 'error');
            }
        });

        return false;
    });

    // GUARDAR EDICIÓN PROSPECTO
    $(document).off('submit', '#form-editar-prospecto').on('submit', '#form-editar-prospecto', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $form.find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        $.post('../controllers/ProspectoController.php', $form.serialize() + '&accion=guardar_edicion', function (res) {
            $btn.prop('disabled', false);
            if (res.trim() === 'ok') {
                Swal.fire('¡Actualizado!', 'Prospecto actualizado correctamente. ✅', 'success');
                $('#formulario-prospecto').off().html('').hide();
                $('#contenedor-tabla-prospecto').show();
                recargarTablaProspectos();
            } else {
                Swal.fire('Error', 'No se pudo actualizar el prospecto. ❌', 'error');
            }
        });

        return false;
    });

    // ELIMINAR PROSPECTO
    $(document).off('click', '.btn-eliminar-prospecto').on('click', '.btn-eliminar-prospecto', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar prospecto?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/ProspectoController.php', { accion: 'eliminar', id: id }, function (res) {
                    if (res.trim() === 'ok') {
                        Swal.fire('Eliminado', 'Prospecto eliminado correctamente. ✅', 'success');
                        $('#formulario-prospecto').off().html('').hide();
                        $('#contenedor-tabla-prospecto').show();
                        recargarTablaProspectos();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar el prospecto. ❌', 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-prospecto').on('click', '#btn-cancelar-prospecto', function () {
        $('#formulario-prospecto').off().html('').hide();
        $('#contenedor-tabla-prospecto').show();
        recargarTablaProspectos();
    });
});
