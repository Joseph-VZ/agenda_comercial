$(document).ready(function () {
    function inicializarTablaMarcas() {
        if ($.fn.DataTable.isDataTable('#tablaMarcas')) {
            $('#tablaMarcas').DataTable().destroy();
        }

        $('#tablaMarcas').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaMarcas() {
        $.post('../controllers/MarcaController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-marca').html(html);
            inicializarTablaMarcas();
        });
    }

    // NUEVA MARCA
    $(document).off('click', '#btn-nueva-marca').on('click', '#btn-nueva-marca', function () {
        $.post('../controllers/MarcaController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-marca').off().html(response).show();
            $('#contenedor-tabla-marca').hide();
        });
    });

    // EDITAR MARCA
    $(document).off('click', '.btn-editar-marca').on('click', '.btn-editar-marca', function () {
        const id = $(this).data('id');
        $.post('../controllers/MarcaController.php', { accion: 'formulario_editar', id: id }, function (response) {
            $('#formulario-marca').off().html(response).show();
            $('#contenedor-tabla-marca').hide();
        });
    });

    // GUARDAR NUEVA MARCA
    $(document).off('submit', '#form-nueva-marca').on('submit', '#form-nueva-marca', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo'); 

        $.ajax({
            url: '../controllers/MarcaController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Éxito!', 'Marca registrada correctamente. ✅', 'success');
                    $('#formulario-marca').off().html('').hide();
                    $('#contenedor-tabla-marca').show();
                    recargarTablaMarcas();
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


    // GUARDAR EDICIÓN MARCA
    $(document).off('submit', '#form-editar-marca').on('submit', '#form-editar-marca', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        $.ajax({
            url: '../controllers/MarcaController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Actualizado!', 'Cambios guardados correctamente. ✅', 'success');
                    $('#formulario-marca').off().html('').hide();
                    $('#contenedor-tabla-marca').show();
                    recargarTablaMarcas();
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

    // ELIMINAR MARCA
    $(document).off('click', '.btn-eliminar-marca').on('click', '.btn-eliminar-marca', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar marca?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/MarcaController.php', { accion: 'eliminar', id: id }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Eliminado', 'Marca eliminada correctamente. ✅', 'success');
                        $('#formulario-marca').off().html('').hide();
                        $('#contenedor-tabla-marca').show();
                        recargarTablaMarcas();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar: ' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-marca').on('click', '#btn-cancelar-marca', function () {
        $('#formulario-marca').off().html('').hide();
        $('#contenedor-tabla-marca').show();
        recargarTablaMarcas();
    });

    // Cargar tabla al inicio
    recargarTablaMarcas();
});
