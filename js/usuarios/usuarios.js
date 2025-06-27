$(document).ready(function () {
    function inicializarTablaUsuarios() {
        if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
            $('#tablaUsuarios').DataTable().destroy();
        }

        $('#tablaUsuarios').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });
    }

    function recargarTablaUsuarios() {
        $.post('../controllers/UsuarioController.php', { accion: 'tabla' }, function (html) {
            $('#contenedor-tabla-usuario').html(html);
            inicializarTablaUsuarios();
        });
    }

    // NUEVO USUARIO
    $(document).off('click', '#btn-nuevo-usuario').on('click', '#btn-nuevo-usuario', function () {
        $.post('../controllers/UsuarioController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-usuario').off().html(response).show(); // Limpia eventos previos
            $('#contenedor-tabla-usuario').hide();
        });
    });

    // EDITAR USUARIO
    $(document).off('click', '.btn-editar-usuario').on('click', '.btn-editar-usuario', function () {
        const usuarioId = $(this).data('id');
        $.post('../controllers/UsuarioController.php', { accion: 'formulario_editar', id: usuarioId }, function (response) {
            $('#formulario-usuario').off().html(response).show();
            $('#contenedor-tabla-usuario').hide();
        });
    });

    // GUARDAR NUEVO USUARIO
    $(document).off('submit', '#form-nuevo-usuario').on('submit', '#form-nuevo-usuario', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        $('#correo-error').text('');
        $('#correo').removeClass('is-invalid');

        const datos = new FormData($form);
        datos.append('accion', 'guardar_nuevo');

        for (let pair of datos.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }

        $.ajax({
            url: '../controllers/UsuarioController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Éxito!', 'Usuario registrado exitosamente. ✅', 'success');
                    $('#formulario-usuario').off().html('').hide();
                    $('#contenedor-tabla-usuario').show();
                    recargarTablaUsuarios();
                } else if (response.trim() === 'correo_duplicado') {
                    $('#correo-error').text('Este correo ya está registrado.');
                    $('#correo').addClass('is-invalid').val('').focus();
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

    // GUARDAR EDICIÓN USUARIO
    $(document).off('submit', '#form-editar-usuario').on('submit', '#form-editar-usuario', function (e) {
        e.preventDefault();

        const $form = $(this)[0];
        const $btn = $(this).find('button[type=submit]');
        if ($btn.prop('disabled')) return false;
        $btn.prop('disabled', true);

        $('#correo-error').text('');
        $('#correo').removeClass('is-invalid');

        const datos = new FormData($form);
        datos.append('accion', 'guardar_edicion');

        for (let pair of datos.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }

        $.ajax({
            url: '../controllers/UsuarioController.php',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (response) {
                $btn.prop('disabled', false);
                if (response.trim() === 'ok') {
                    Swal.fire('¡Actualizado!', 'Cambios guardados correctamente. ✅', 'success');
                    $('#formulario-usuario').off().html('').hide();
                    $('#contenedor-tabla-usuario').show();
                    recargarTablaUsuarios();
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

    // ELIMINAR --> DESACTIVAR
    $(document).off('click', '.btn-desactivar-usuario').on('click', '.btn-desactivar-usuario', function () {
        const usuarioId = $(this).data('id');
        Swal.fire({
            title: 'Desactivar usuario?',
            text: 'El usuario se desactivará.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/UsuarioController.php', { accion: 'desactivar', id: usuarioId }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Desactivado', 'Usuario desactivado. ✅', 'success');
                        $('#formulario-usuario').off().html('').hide();
                        $('#contenedor-tabla-usuario').show();
                        recargarTablaUsuarios();
                    } else {
                        Swal.fire('Error', 'No se pudo desactivar: ' + response, 'error');
                    }
                });
            }
        });
    });

    // CANCELAR
    $(document).off('click', '#btn-cancelar-usuario').on('click', '#btn-cancelar-usuario', function () {
        $('#formulario-usuario').off().html('').hide();
        $('#contenedor-tabla-usuario').show();
        recargarTablaUsuarios();
    });

    // VERIFICAR CORREO
    $(document).on('blur', '#correo', function () {
        const correo = $(this).val().trim();
        const usuarioId = $('input[name=id]').val() || '';
        $('#correo-error').text('');
        $('#correo').removeClass('is-invalid');

        if (correo !== '') {
            $.ajax({
                url: '../controllers/UsuarioController.php',
                type: 'POST',
                data: {
                    accion: 'verificarCorreo',
                    correo: correo,
                    id: usuarioId
                },
                success: function (response) {
                    if (response.trim() === 'existe') {
                        $('#correo-error').text('Este correo ya está registrado. Elige otro.');
                        $('#correo').addClass('is-invalid').focus();
                    }
                }
            });
        }
    });

    // ACTIVAR USUARIO
    $(document).off('click', '.btn-activar-usuario').on('click', '.btn-activar-usuario', function () {
        const usuarioId = $(this).data('id');
        Swal.fire({
            title: '¿Activar usuario?',
            text: 'Este usuario volverá a estar activo.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controllers/UsuarioController.php', { accion: 'activar', id: usuarioId }, function (response) {
                    if (response.trim() === 'ok') {
                        Swal.fire('Activado', 'Usuario activado correctamente. ✅', 'success');
                        recargarTablaUsuarios();
                    } else {
                        Swal.fire('Error', 'No se pudo activar: ' + response, 'error');
                    }
                });
            }
        });
    });


    recargarTablaUsuarios();
});
