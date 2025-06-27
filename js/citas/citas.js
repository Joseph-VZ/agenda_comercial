var calendar;

function iniciarModuloCitas() {
    inicializarCalendario();

    $('#btn-nueva-cita').off('click').on('click', function () {
        $.post('../controllers/CitaController.php', { accion: 'formulario_nuevo' }, function (html) {
            $('#modal-cita-body').html(html);

            $('#form-nueva-cita').off('submit').on('submit', enviarFormularioCita);

            const modal = new bootstrap.Modal(document.getElementById('modal-cita'));
            modal.show();
        });
    });

    $('#modal-cita').off('hidden.bs.modal').on('hidden.bs.modal', function () {
        limpiarBackdrop();
    });
}

function inicializarCalendario() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        eventSources: [
            {
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '../controllers/CitaController.php',
                        method: 'POST',
                        data: { accion: 'eventos' },
                        dataType: 'json'
                    })
                    .done(function(data) {
                        successCallback(data);
                    })
                    .fail(function() {
                        alert('Error al cargar eventos locales');
                        failureCallback();
                    });
                }
            },
            {
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '../controllers/CitaController.php',
                        method: 'POST',
                        data: { accion: 'eventos_google' },
                        dataType: 'json'
                    })
                    .done(function(data) {
                        successCallback(data);
                    })
                    .fail(function() {
                        failureCallback();
                    });
                },
                color: '#4285F4',
                textColor: 'white'
            }
        ],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function (info) {
            const evento = info.event;

            if (evento.id.startsWith('google_')) {
                Swal.fire({
                    title: 'Evento de Google Calendar',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: 'Editar',
                    denyButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('../controllers/CitaController.php', {
                            accion: 'formulario_editar_google',
                            id: evento.id
                        }, function (html) {
                            $('#modal-cita-body').html(html);

                            // Formulario edición Google
                            $('#form-editar-google').off('submit').on('submit', function (e) {
                                e.preventDefault();

                                const datos = $(this).serialize();

                                $.post('../controllers/CitaController.php', {
                                    accion: 'guardar_edicion_evento_google',
                                    ...Object.fromEntries(new URLSearchParams(datos))
                                }, function (respuesta) {
                                    try {
                                        const res = JSON.parse(respuesta);
                                        if (res.success) {
                                            calendar.refetchEvents();
                                            Swal.fire('Actualizado', 'El evento fue editado.', 'success');
                                            const modal = bootstrap.Modal.getInstance(document.getElementById('modal-cita'));
                                            modal.hide();
                                        } else {
                                            Swal.fire('Error', res.error || 'No se pudo editar el evento', 'error');
                                        }
                                    } catch (e) {
                                        Swal.fire('Error', 'Respuesta inválida del servidor.', 'error');
                                    }
                                }).fail(() => {
                                    Swal.fire('Error', 'Error de comunicación con el servidor.', 'error');
                                });
                            });

                            const modal = new bootstrap.Modal(document.getElementById('modal-cita'));
                            modal.show();
                        });

                    } else if (result.isDenied) {
                        Swal.fire({
                            title: '¿Eliminar evento de Google Calendar?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((confirmacion) => {
                            if (confirmacion.isConfirmed) {
                                $.post('../controllers/CitaController.php', {
                                    accion: 'eliminar_evento_google',
                                    id: evento.id.replace('google_', '')
                                }, function (respuesta) {
                                    try {
                                        const res = JSON.parse(respuesta);
                                        if (res.success) {
                                            calendar.refetchEvents();
                                            Swal.fire('Eliminado', 'El evento fue eliminado.', 'success');
                                        } else {
                                            Swal.fire('Error', res.error || 'No se pudo eliminar el evento.', 'error');
                                        }
                                    } catch (e) {
                                        Swal.fire('Error', 'Respuesta inválida del servidor.', 'error');
                                    }
                                }).fail(() => {
                                    Swal.fire('Error', 'Error al eliminar el evento.', 'error');
                                });
                            }
                        });
                    }
                });
            } else {
                // Evento local
                $.post('../controllers/CitaController.php', {
                    accion: 'formulario_editar',
                    id: evento.id
                }, function (html) {
                    $('#modal-cita-body').html(html);
                    $('#form-editar-cita').off('submit').on('submit', enviarFormularioCita);
                    const modal = new bootstrap.Modal(document.getElementById('modal-cita'));
                    modal.show();
                });
            }
        },
        dateClick: function (info) {
            $.post('../controllers/CitaController.php', { accion: 'formulario_nuevo', fecha: info.dateStr }, function (html) {
                $('#modal-cita-body').html(html);
                $('#form-nueva-cita').off('submit').on('submit', enviarFormularioCita);
                const modal = new bootstrap.Modal(document.getElementById('modal-cita'));
                modal.show();
            });
        }
    });

    calendar.render();
}

function enviarFormularioCita(e) {
    e.preventDefault();

    const formulario = $(this)[0]; // el DOM puro
    const formData = new FormData(formulario);
    formData.append('accion', formulario.id === 'form-nueva-cita' ? 'guardar_nuevo' : 'guardar_edicion');

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).text('Guardando...');

    $.ajax({
        url: '../controllers/CitaController.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (respuesta) {
            if (respuesta.trim() === 'ok') {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal-cita'));
                modal.hide();
                limpiarBackdrop();

                Swal.fire({
                    icon: 'success',
                    title: 'Cita guardada correctamente',
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false
                });

                if (calendar) {
                    calendar.refetchEvents();
                }
            } else {
                Swal.fire('Error', respuesta, 'error');
            }

            submitBtn.prop('disabled', false).text('Guardar');
        },
        error: function () {
            Swal.fire('Error', 'No se pudo guardar la cita.', 'error');
            submitBtn.prop('disabled', false).text('Guardar');
        }
    });
}


$(document).on('click', '.btn-eliminar-cita', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: '¿Eliminar esta cita?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('../controllers/CitaController.php', { accion: 'eliminar', id }, function (respuesta) {
                if (respuesta.trim() === 'ok') {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modal-cita'));
                    modal.hide();
                    limpiarBackdrop();

                    Swal.fire({
                        icon: 'success',
                        title: 'Cita eliminada correctamente',
                        toast: true,
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    if (calendar) {
                        calendar.refetchEvents();
                    }
                } else {
                    Swal.fire('Error', respuesta, 'error');
                }
            }).fail(() => {
                Swal.fire('Error', 'No se pudo eliminar la cita.', 'error');
            });
        }
    });
});

$(document).on('click', '#btn-cancelar-cita', function () {
    const modal = bootstrap.Modal.getInstance(document.getElementById('modal-cita'));
    if (modal) modal.hide();
    limpiarBackdrop();
});

function limpiarBackdrop() {
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('padding-right', '');
}

$(document).ready(function () {
    iniciarModuloCitas();
});
