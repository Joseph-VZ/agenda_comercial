$(document).ready(function () {
    function inicializarTablaProductos() {
        if ($.fn.DataTable.isDataTable('#tablaProductos')) {
            $('#tablaProductos').DataTable().destroy();
        }

        $('#tablaProductos').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaProductos() {
        $.post('../controllers/ProductoController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-producto').html(html);
            inicializarTablaProductos();
        });
    }

    // NUEVO PRODUCTO
    $(document).off('click', '#btn-nuevo-producto').on('click', '#btn-nuevo-producto', function () {
        $.post('../controllers/ProductoController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-producto').off().html(response).show();
            $('#contenedor-tabla-producto').hide();
        });
    });

    // EDITAR PRODUCTO
    $(document).off('click', '.btn-editar-producto').on('click', '.btn-editar-producto', function () {
        const id = $(this).data('id');
        $.post('../controllers/ProductoController.php', { accion: 'formulario_editar', id }, function (response) {
            $('#formulario-producto').off().html(response).show();
            $('#contenedor-tabla-producto').hide();
        });
    });

    // GUARDAR NUEVO PRODUCTO
    $(document).off('submit', '#form-nuevo-producto').on('submit', '#form-nuevo-producto', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo');

        $.ajax({
            url: '../controllers/ProductoController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('Éxito', 'Producto registrado exitosamente. ✅', 'success');
                    $('#formulario-producto').off().html('').hide();
                    $('#contenedor-tabla-producto').show();
                    recargarTablaProductos();
                } else {
                    Swal.fire('Error', 'Error al guardar: ' + response, 'error');
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                Swal.fire('Error', 'Error al enviar el formulario.', 'error');
            }
        });

        return false;
    });

    // GUARDAR EDICIÓN PRODUCTO
    $(document).off('submit', '#form-editar-producto').on('submit', '#form-editar-producto', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        $.ajax({
            url: '../controllers/ProductoController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('Actualizado', 'Producto editado correctamente. ✅', 'success');
                    $('#formulario-producto').off().html('').hide();
                    $('#contenedor-tabla-producto').show();
                    recargarTablaProductos();
                } else {
                    Swal.fire('Error', 'Error al editar: ' + response, 'error');
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                Swal.fire('Error', 'Error al enviar el formulario.', 'error');
            }
        });

        return false;
    });

    // DESACTIVAR PRODUCTO
    $(document).off('click', '.btn-desactivar-producto').on('click', '.btn-desactivar-producto', function () {
        console.log("CLICK DESACTIVAR");
        const productoId = $(this).data('id');
        Swal.fire({
            title: '¿Desactivar producto?',
            text: 'Este producto será marcado como inactivo.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/ProductoController.php', { accion: 'desactivar', id: productoId }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Desactivado', 'Producto desactivado correctamente. ✅', 'success');
                        $('#formulario-producto').off().html('').hide();
                        $('#contenedor-tabla-producto').show();
                        recargarTablaProductos();
                    } else {
                        Swal.fire('Error', 'No se pudo desactivar: ' + response, 'error');
                    }
                });
            }
        });
    });


    // ACTIVAR PRODUCTO
    $(document).off('click', '.btn-activar-producto').on('click', '.btn-activar-producto', function () {
        const productoId = $(this).data('id');
        Swal.fire({
            title: '¿Activar producto?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/ProductoController.php', {
                    accion: 'activar',
                    id: productoId
                }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Activado', 'Producto activado correctamente. ✅', 'success');
                        $('#formulario-producto').off().html('').hide();
                        $('#contenedor-tabla-producto').show();
                        recargarTablaProductos();
                    } else {
                        Swal.fire('Error', 'No se pudo activar: ' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-producto').on('click', '#btn-cancelar-producto', function () {
        $('#formulario-producto').off().html('').hide();
        $('#contenedor-tabla-producto').show();
        recargarTablaProductos();
    });

    recargarTablaProductos();
});
