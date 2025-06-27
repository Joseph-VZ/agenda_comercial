$(document).ready(function () {
    function inicializarTablaSubcategorias() {
        if ($.fn.DataTable.isDataTable('#tablaSubcategorias')) {
            $('#tablaSubcategorias').DataTable().destroy();
        }

        $('#tablaSubcategorias').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaSubcategorias() {
        $.post('../controllers/SubcategoriaController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-subcategoria').html(html);
            inicializarTablaSubcategorias();
        });
    }

    // NUEVA SUBCATEGORÍA
    $(document).off('click', '#btn-nueva-subcategoria').on('click', '#btn-nueva-subcategoria', function () {
        $.post('../controllers/SubcategoriaController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-subcategoria').off().html(response).show();
            $('#contenedor-tabla-subcategoria').hide();
        });
    });

    // EDITAR SUBCATEGORÍA
    $(document).off('click', '.btn-editar-subcategoria').on('click', '.btn-editar-subcategoria', function () {
        const id = $(this).data('id');
        $.post('../controllers/SubcategoriaController.php', { accion: 'formulario_editar', id: id }, function (response) {
            $('#formulario-subcategoria').off().html(response).show();
            $('#contenedor-tabla-subcategoria').hide();
        });
    });

    // GUARDAR NUEVA SUBCATEGORÍA
    $(document).off('submit', '#form-nueva-subcategoria').on('submit', '#form-nueva-subcategoria', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo');

        $.ajax({
            url: '../controllers/SubcategoriaController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Éxito!', 'Subcategoría registrada correctamente. ✅', 'success');
                    $('#formulario-subcategoria').off().html('').hide();
                    $('#contenedor-tabla-subcategoria').show();
                    recargarTablaSubcategorias();
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

    // GUARDAR EDICIÓN DE SUBCATEGORÍA
    $(document).off('submit', '#form-editar-subcategoria').on('submit', '#form-editar-subcategoria', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        $.ajax({
            url: '../controllers/SubcategoriaController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Actualizado!', 'Cambios guardados correctamente. ✅', 'success');
                    $('#formulario-subcategoria').off().html('').hide();
                    $('#contenedor-tabla-subcategoria').show();
                    recargarTablaSubcategorias();
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

    // ELIMINAR SUBCATEGORÍA
    $(document).off('click', '.btn-eliminar-subcategoria').on('click', '.btn-eliminar-subcategoria', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar subcategoría?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/SubcategoriaController.php', { accion: 'eliminar', id: id }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Eliminado', 'Subcategoría eliminada correctamente. ✅', 'success');
                        $('#formulario-subcategoria').off().html('').hide();
                        $('#contenedor-tabla-subcategoria').show();
                        recargarTablaSubcategorias();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar: ' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-subcategoria').on('click', '#btn-cancelar-subcategoria', function () {
        $('#formulario-subcategoria').off().html('').hide();
        $('#contenedor-tabla-subcategoria').show();
        recargarTablaSubcategorias();
    });


    recargarTablaSubcategorias();
});
