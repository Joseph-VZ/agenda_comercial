$(document).ready(function () {
    function inicializarTablaMunicipios() {
        if ($.fn.DataTable.isDataTable('#tablaMunicipios')) {
            $('#tablaMunicipios').DataTable().destroy();
        }

        $('#tablaMunicipios').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaMunicipios() {
        $.post('../controllers/MunicipioController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-municipio').html(html);
            inicializarTablaMunicipios();
        });
    }

    // NUEVO MUNICIPIO
    $(document).off('click', '#btn-nuevo-municipio').on('click', '#btn-nuevo-municipio', function () {
        $.post('../controllers/MunicipioController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-municipio').off().html(response).show();
            $('#contenedor-tabla-municipio').hide();
        });
    });

    // EDITAR MUNICIPIO
    $(document).off('click', '.btn-editar-municipio').on('click', '.btn-editar-municipio', function () {
        const id = $(this).data('id');
        $.post('../controllers/MunicipioController.php', { accion: 'formulario_editar', id: id }, function (response) {
            $('#formulario-municipio').off().html(response).show();
            $('#contenedor-tabla-municipio').hide();
        });
    });

    // GUARDAR NUEVO MUNICIPIO
    $(document).off('submit', '#form-nuevo-municipio').on('submit', '#form-nuevo-municipio', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo');

        $.ajax({
            url: '../controllers/MunicipioController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Éxito!', 'Municipio registrado correctamente. ✅', 'success');
                    $('#formulario-municipio').off().html('').hide();
                    $('#contenedor-tabla-municipio').show();
                    recargarTablaMunicipios();
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

    // GUARDAR EDICIÓN MUNICIPIO
    $(document).off('submit', '#form-editar-municipio').on('submit', '#form-editar-municipio', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        $.ajax({
            url: '../controllers/MunicipioController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Actualizado!', 'Cambios guardados correctamente. ✅', 'success');
                    $('#formulario-municipio').off().html('').hide();
                    $('#contenedor-tabla-municipio').show();
                    recargarTablaMunicipios();
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

    // ELIMINAR MUNICIPIO
    $(document).off('click', '.btn-eliminar-municipio').on('click', '.btn-eliminar-municipio', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar municipio?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/MunicipioController.php', { accion: 'eliminar', id: id }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Eliminado', 'Municipio eliminado correctamente. ✅', 'success');
                        $('#formulario-municipio').off().html('').hide();
                        $('#contenedor-tabla-municipio').show();
                        recargarTablaMunicipios();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar: ' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-municipio').on('click', '#btn-cancelar-municipio', function () {
        $('#formulario-municipio').off().html('').hide();
        $('#contenedor-tabla-municipio').show();
        recargarTablaMunicipios();
    });

    recargarTablaMunicipios();
});
