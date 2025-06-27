$(document).ready(function () {
    function inicializarTablaZonas() {
        if ($.fn.DataTable.isDataTable('#tablaZonas')) {
            $('#tablaCategorias').DataTable().destroy();
        }

        $('#tablaZonas').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaZonas() {
        $.post('../controllers/ZonaController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-zona').html(html);
            inicializarTablaZonas();
        });
    }

    // NUEVA CATEGORÍA
    $(document).off('click', '#btn-nueva-zona').on('click', '#btn-nueva-zona', function () {
        $.post('../controllers/ZonaController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-zona').off().html(response).show();
            $('#contenedor-tabla-zona').hide();
        });
    });

    // EDITAR CATEGORÍA
    $(document).off('click', '.btn-editar-zona').on('click', '.btn-editar-zona', function () {
        const id = $(this).data('id');
        $.post('../controllers/ZonaController.php', { accion: 'formulario_editar', id: id }, function (response) {
            $('#formulario-zona').off().html(response).show();
            $('#contenedor-tabla-zona').hide();
        });
    });

    // GUARDAR NUEVA CATEGORÍA
    $(document).off('submit', '#form-nueva-zona').on('submit', '#form-nueva-zona', function (e) {
        e.preventDefault();

        const $form = this;
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo');

        $.ajax({
            url: '../controllers/ZonaController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Éxito!', 'Zona registrada correctamente. ✅', 'success');
                    $('#formulario-zona').off().html('').hide();
                    $('#contenedor-tabla-zona').show();
                    recargarTablaZonas();
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

    // GUARDAR EDICIÓN CATEGORÍA
    $(document).off('submit', '#form-editar-zona').on('submit', '#form-editar-zona', function (e) {
        e.preventDefault();

        const $form = this;
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        $.ajax({
            url: '../controllers/ZonaController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Actualizado!', 'Cambios guardados correctamente. ✅', 'success');
                    $('#formulario-zona').off().html('').hide();
                    $('#contenedor-tabla-zona').show();
                    recargarTablaZonas();
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

    // ELIMINAR CATEGORÍA
    $(document).off('click', '.btn-eliminar-zona').on('click', '.btn-eliminar-zona', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar zona?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/ZonaController.php', { accion: 'eliminar', id: id }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Eliminado', 'Zona eliminada correctamente. ✅', 'success');
                        $('#formulario-zona').off().html('').hide();
                        $('#contenedor-tabla-zona').show();
                        recargarTablaZonas();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar: ' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-zona').on('click', '#btn-cancelar-zona', function () {
        $('#formulario-zona').off().html('').hide();
        $('#contenedor-tabla-zona').show();
        recargarTablaZona();
    });


    recargarTablaZonas();
});
