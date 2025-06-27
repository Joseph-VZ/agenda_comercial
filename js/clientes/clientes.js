$(document).ready(function () {
    function inicializarTablaClientes() {
        if ($.fn.DataTable.isDataTable('#tablaClientes')) {
            $('#tablaClientes').DataTable().destroy();
        }

        $('#tablaClientes').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaClientes() {
        $.post('../controllers/ClienteController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-cliente').html(html);
            inicializarTablaClientes();
        });
    }

    // NUEVO CLIENTE
    $(document).off('click', '#btn-nuevo-cliente').on('click', '#btn-nuevo-cliente', function () {
        $.post('../controllers/ClienteController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-cliente').off().html(response).show();
            $('#contenedor-tabla-cliente').hide();
        });
    });

    // EDITAR CLIENTE
    $(document).off('click', '.btn-editar-cliente').on('click', '.btn-editar-cliente', function () {
        const clienteId = $(this).data('id');
        $.post('../controllers/ClienteController.php', { accion: 'formulario_editar', id: clienteId }, function (response) {
            $('#formulario-cliente').off().html(response).show();
            $('#contenedor-tabla-cliente').hide();
        });
    });

    // GUARDAR NUEVO CLIENTE
    $(document).off('submit', '#form-nuevo-cliente').on('submit', '#form-nuevo-cliente', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo');

        $.ajax({
            url: '../controllers/ClienteController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Éxito!', 'Cliente registrado exitosamente. ✅', 'success');
                    $('#formulario-cliente').off().html('').hide();
                    $('#contenedor-tabla-cliente').show();
                    recargarTablaClientes();
                } else {
                    Swal.fire('Error', 'No se pudo guardar:\n' + response, 'error');
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                Swal.fire('Error', 'Error al enviar el formulario.', 'error');
            }
        });

        return false;
    });

    // GUARDAR EDICIÓN CLIENTE
    $(document).off('submit', '#form-editar-cliente').on('submit', '#form-editar-cliente', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        $.ajax({
            url: '../controllers/ClienteController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Actualizado!', 'Cambios guardados correctamente. ✅', 'success');
                    $('#formulario-cliente').off().html('').hide();
                    $('#contenedor-tabla-cliente').show();
                    recargarTablaClientes();
                } else {
                    Swal.fire('Error', 'No se pudo editar:\n' + response, 'error');
                }
            },
            error: function () {
                $btn.prop('disabled', false);
                Swal.fire('Error', 'Error al enviar el formulario.', 'error');
            }
        });

        return false;
    });

    // DESACTIVAR CLIENTE
    $(document).off('click', '.btn-desactivar-cliente').on('click', '.btn-desactivar-cliente', function () {
        const clienteId = $(this).data('id');
        Swal.fire({
            title: '¿Desactivar cliente?',
            text: 'Este cliente será marcado como inactivo.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/ClienteController.php', { accion: 'desactivar', id: clienteId }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Desactivado', 'Cliente desactivado correctamente. ✅', 'success');
                        $('#formulario-cliente').off().html('').hide();
                        $('#contenedor-tabla-cliente').show();
                        recargarTablaClientes();
                    } else {
                        Swal.fire('Error', 'No se pudo desactivar:\n' + response, 'error');
                    }
                });
            }
        });
    });

    // ACTIVAR CLIENTE
    $(document).off('click', '.btn-activar-cliente').on('click', '.btn-activar-cliente', function () {
        const clienteId = $(this).data('id');
        Swal.fire({
            title: '¿Activar cliente?',
            text: 'Este cliente volverá a estar activo.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/ClienteController.php', { accion: 'activar', id: clienteId }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Activado', 'Cliente activado correctamente. ✅', 'success');
                        recargarTablaClientes();
                    } else {
                        Swal.fire('Error', 'No se pudo activar:\n' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-cliente').on('click', '#btn-cancelar-cliente', function () {
        $('#formulario-cliente').off().html('').hide();
        $('#contenedor-tabla-cliente').show();
        recargarTablaClientes();
    });

    recargarTablaClientes();
});
