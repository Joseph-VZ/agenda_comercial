$(document).ready(function () {
    function inicializarTablaCategorias() {
        if ($.fn.DataTable.isDataTable('#tablaCategorias')) {
            $('#tablaCategorias').DataTable().destroy();
        }

        $('#tablaCategorias').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaCategorias() {
        $.post('../controllers/CategoriaController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-categoria').html(html);
            inicializarTablaCategorias();
        });
    }

    // NUEVA CATEGORÍA
    $(document).off('click', '#btn-nueva-categoria').on('click', '#btn-nueva-categoria', function () {
        $.post('../controllers/CategoriaController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-categoria').off().html(response).show();
            $('#contenedor-tabla-categoria').hide();
        });
    });

    // EDITAR CATEGORÍA
    $(document).off('click', '.btn-editar-categoria').on('click', '.btn-editar-categoria', function () {
        const id = $(this).data('id');
        $.post('../controllers/CategoriaController.php', { accion: 'formulario_editar', id: id }, function (response) {
            $('#formulario-categoria').off().html(response).show();
            $('#contenedor-tabla-categoria').hide();
        });
    });

    // GUARDAR NUEVA CATEGORÍA
    $(document).off('submit', '#form-nueva-categoria').on('submit', '#form-nueva-categoria', function (e) {
        e.preventDefault();

        const $form = this;
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo');

        $.ajax({
            url: '../controllers/CategoriaController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Éxito!', 'Categoría registrada correctamente. ✅', 'success');
                    $('#formulario-categoria').off().html('').hide();
                    $('#contenedor-tabla-categoria').show();
                    recargarTablaCategorias();
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
    $(document).off('submit', '#form-editar-categoria').on('submit', '#form-editar-categoria', function (e) {
        e.preventDefault();

        const $form = this;
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        $.ajax({
            url: '../controllers/CategoriaController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Actualizado!', 'Cambios guardados correctamente. ✅', 'success');
                    $('#formulario-categoria').off().html('').hide();
                    $('#contenedor-tabla-categoria').show();
                    recargarTablaCategorias();
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
    $(document).off('click', '.btn-eliminar-categoria').on('click', '.btn-eliminar-categoria', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar categoría?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/CategoriaController.php', { accion: 'eliminar', id: id }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Eliminado', 'Categoría eliminada correctamente. ✅', 'success');
                        $('#formulario-categoria').off().html('').hide();
                        $('#contenedor-tabla-categoria').show();
                        recargarTablaCategorias();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar: ' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-categoria').on('click', '#btn-cancelar-categoria', function () {
        $('#formulario-categoria').off().html('').hide();
        $('#contenedor-tabla-categoria').show();
        recargarTablaCategorias();
    });

    // Cargar tabla al inicio
    recargarTablaCategorias();
});
