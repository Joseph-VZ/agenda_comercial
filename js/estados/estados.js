$(document).ready(function () {
    function inicializarTablaEstados() {
        if ($.fn.DataTable.isDataTable('#tablaEstados')) {
            $('#tablaEstados').DataTable().destroy();
        }

        $('#tablaEstados').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaEstados() {
        $.post('../controllers/EstadoController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-estado').html(html);
            inicializarTablaEstados();
        });
    }

    // NUEVO ESTADO
    $(document).off('click', '#btn-nuevo-estado').on('click', '#btn-nuevo-estado', function () {
        $.post('../controllers/EstadoController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-estado').off().html(response).show();
            $('#contenedor-tabla-estado').hide();
        });
    });

    // EDITAR ESTADO
    $(document).off('click', '.btn-editar-estado').on('click', '.btn-editar-estado', function () {
        const id = $(this).data('id');
        $.post('../controllers/EstadoController.php', { accion: 'formulario_editar', id: id }, function (response) {
            $('#formulario-estado').off().html(response).show();
            $('#contenedor-tabla-estado').hide();
        });
    });

    // GUARDAR NUEVO ESTADO
    $(document).off('submit', '#form-nuevo-estado').on('submit', '#form-nuevo-estado', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo');

        $.ajax({
            url: '../controllers/EstadoController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Éxito!', 'Estado registrado correctamente. ✅', 'success');
                    $('#formulario-estado').off().html('').hide();
                    $('#contenedor-tabla-estado').show();
                    recargarTablaEstados();
                } else {
                    Swal.fire('Error', 'No se pudo guardar: ' + response, 'error');
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                Swal.fire('Error', 'Error al enviar el formulario.', 'error');
            }
        });

        return false;
    });

    // GUARDAR EDICIÓN ESTADO
    $(document).off('submit', '#form-editar-estado').on('submit', '#form-editar-estado', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        $.ajax({
            url: '../controllers/EstadoController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Actualizado!', 'Cambios guardados correctamente. ✅', 'success');
                    $('#formulario-estado').off().html('').hide();
                    $('#contenedor-tabla-estado').show();
                    recargarTablaEstados();
                } else {
                    Swal.fire('Error', 'No se pudo editar: ' + response, 'error');
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                Swal.fire('Error', 'Error al enviar el formulario.', 'error');
            }
        });

        return false;
    });

    // ELIMINAR ESTADO
    $(document).off('click', '.btn-eliminar-estado').on('click', '.btn-eliminar-estado', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar estado?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/EstadoController.php', { accion: 'eliminar', id: id }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Eliminado', 'Estado eliminado correctamente. ✅', 'success');
                        $('#formulario-estado').off().html('').hide();
                        $('#contenedor-tabla-estado').show();
                        recargarTablaEstados();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar: ' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-estado').on('click', '#btn-cancelar-estado', function () {
        $('#formulario-estado').off().html('').hide();
        $('#contenedor-tabla-estado').show();
        recargarTablaEstados();
    });

    recargarTablaEstados();
});
