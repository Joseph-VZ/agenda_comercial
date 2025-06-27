$(document).ready(function () {
    let tabla;
    let productosAgregados = [];

    function formatearFechaEspanol(fechaISO) {
        const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        const fechaSolo = fechaISO.split(' ')[0];
        const partes = fechaSolo.split('-');
        const dia = parseInt(partes[2], 10);
        const mes = meses[parseInt(partes[1], 10) - 1];
        const anio = partes[0];
        return `${dia} de ${mes} de ${anio}`;
    }

    function inicializarTablaVentas() {
        tabla = $('#tablaVentas').DataTable({
            ajax: {
                url: '../controllers/VentaController.php',
                type: 'POST',
                data: { accion: 'tabla' }
            },
            columns: [
                { data: 'id' },
                {
                    data: 'fecha',
                    render: function (data, type) {
                        if (type === 'display' || type === 'filter') {
                            return formatearFechaEspanol(data);
                        }
                        return data;
                    }
                },
                { data: 'total' },
                { data: 'nombre_cliente' },
                { data: 'nombre_usuario' },
                { data: 'acciones' }
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            dom: 'Bfrtip',
            buttons: ['excelHtml5', 'pdfHtml5', 'print']
        });

        $('#tablaVentas').on('click', '.btn-ver-detalles', function () {
            const idVenta = $(this).data('id');
            $('#modalDetallesVenta').modal('show');
            $('#contenidoDetallesVenta').html('Cargando detalles...');

            $.post('../controllers/VentaController.php', { accion: 'detalles', id: idVenta }, function (respuesta) {
                $('#contenidoDetallesVenta').html(respuesta);
                $('#btn-descargar-pdf').attr('data-id', idVenta);
            }).fail(function () {
                $('#contenidoDetallesVenta').html('<div class="alert alert-danger">Ocurrió un error al cargar los detalles.</div>');
            });
        });
    }

    function recargarTablaVentas() {
        tabla.ajax.reload(null, false);
    }

    function renderizarTablaProductos() {
        const $tbody = $('#tabla-productos tbody');
        $tbody.empty();

        productosAgregados.forEach((p, index) => {
            const subtotal = (p.precio * p.cantidad).toFixed(2);
            const row = `
                <tr data-index="${index}">
                    <td>${p.nombre}</td>
                    <td>$${p.precio.toFixed(2)}</td>
                    <td><input type="number" class="form-control input-cantidad" value="${p.cantidad}" min="1" data-index="${index}"></td>
                    <td>$${subtotal}</td>
                    <td><button type="button" class="btn btn-danger btn-sm btn-quitar-producto" data-index="${index}">Quitar</button></td>
                </tr>
            `;
            $tbody.append(row);
        });

        recalcularTotal();
        $('#productos-json').val(JSON.stringify(productosAgregados));
    }

    function recalcularTotal() {
        const total = productosAgregados.reduce((acc, p) => acc + (p.precio * p.cantidad), 0);
        $('#total-general').text(total.toFixed(2));
    }

    function asignarEventosVentas() {
        // Editar
        $(document).off('click', '.btn-editar-venta').on('click', '.btn-editar-venta', function () {
            const ventaId = $(this).data('id');

            $.post('../controllers/VentaController.php', { accion: 'formulario_editar', id: ventaId }, function (response) {
                $('#formulario-venta').html(response).show();
                $('#contenedor-tabla-venta').hide();

                setTimeout(() => {
                    try {
                        const json = $('#productos-json').val();
                        productosAgregados = JSON.parse(json || '[]');
                        renderizarTablaProductos();
                    } catch (e) {
                        productosAgregados = [];
                        console.error("Error al parsear productos_json:", e);
                    }
                }, 50);
            });
        });

        // Eliminar
        $(document).off('click', '.btn-eliminar-venta').on('click', '.btn-eliminar-venta', function () {
            const ventaId = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar venta?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../controllers/VentaController.php', { accion: 'eliminar', id: ventaId }, function (response) {
                        if (response.trim() === 'ok') {
                            Swal.fire('Eliminado', 'Venta eliminada correctamente. ✅', 'success');
                        } else if (response.trim() === 'desactivado') {
                            Swal.fire('Atención', 'La venta fue desactivada.', 'warning');
                        } else {
                            Swal.fire('Error', 'Error: ' + response, 'error');
                        }

                        $('#formulario-venta').html('').hide();
                        $('#contenedor-tabla-venta').show();
                        recargarTablaVentas();
                    });
                }
            });
        });

        // Ver detalles (duplicado por compatibilidad)
        $(document).off('click', '.btn-detalles-venta').on('click', '.btn-detalles-venta', function () {
            const ventaId = $(this).data('id');
            $('#btn-descargar-pdf').data('id', ventaId);

            $.post('../controllers/VentaController.php', { accion: 'detalles', id: ventaId }, function (response) {
                $('#contenidoDetallesVenta').html(response);
                const modal = new bootstrap.Modal(document.getElementById('modalDetallesVenta'));
                modal.show();
            });
        });
    }

    // Crear nueva venta
    $(document).on('click', '#btn-nueva-venta', function () {
        productosAgregados = [];

        $.post('../controllers/VentaController.php', { accion: 'formulario_nuevo' }, function (response) {
            $('#formulario-venta').html(response).show();
            $('#contenedor-tabla-venta').hide();

            setTimeout(() => {
                renderizarTablaProductos();
            }, 50);
        });
    });

    // Agregar producto
    $(document).on('click', '#btn-agregar-producto', function () {
        const $select = $('#producto-select');
        const id = parseInt($select.val());
        const nombre = $select.find('option:selected').text();
        const precio = parseFloat($select.find('option:selected').data('precio'));
        const cantidad = parseInt($('#cantidad-input').val());

        if (!id || isNaN(precio) || isNaN(cantidad) || cantidad <= 0) {
            Swal.fire('Error', 'Selecciona un producto válido y cantidad mayor a 0.', 'warning');
            return;
        }

        const existente = productosAgregados.find(p => p.id === id);
        if (existente) {
            existente.cantidad += cantidad;
        } else {
            productosAgregados.push({ id, nombre, precio, cantidad });
        }

        renderizarTablaProductos();
        $select.val('');
        $('#cantidad-input').val('');
    });

    // Quitar producto
    $(document).on('click', '.btn-quitar-producto', function () {
        const index = parseInt($(this).data('index'));
        productosAgregados.splice(index, 1);
        renderizarTablaProductos();
    });

    // Cambiar cantidad
    $(document).on('input', '.input-cantidad', function () {
        const index = parseInt($(this).data('index'));
        const nuevaCantidad = parseInt($(this).val());
        if (nuevaCantidad > 0) {
            productosAgregados[index].cantidad = nuevaCantidad;
            renderizarTablaProductos();
        }
    });

    // Guardar nueva venta
    $(document).on('submit', '#form-nueva-venta', function (e) {
        e.preventDefault();
        const fecha = $('input[name="fecha"]').val();
        const id_cliente = $('#id_cliente').val();
        const id_usuario = $('#id_usuario').val();

        if (productosAgregados.length === 0) {
            Swal.fire('Advertencia', 'Agrega al menos un producto.', 'warning');
            return;
        }

        const data = {
            accion: 'guardar_nuevo',
            fecha,
            id_cliente,
            id_usuario,
            productos: JSON.stringify(productosAgregados)
        };

        $.post('../controllers/VentaController.php', data, function (response) {
            if (response.trim() === 'ok') {
                Swal.fire('¡Éxito!', 'Venta registrada correctamente ✅', 'success');
                $('#formulario-venta').html('').hide();
                $('#contenedor-tabla-venta').show();
                recargarTablaVentas();
            } else {
                Swal.fire('Error', 'No se pudo guardar: ' + response, 'error');
            }
        });
    });

    // Guardar edición
    $(document).on('submit', '#form-editar-venta', function (e) {
        e.preventDefault();
        const datos = $(this).serializeArray();
        datos.push({ name: 'productos', value: JSON.stringify(productosAgregados) });

        $.post('../controllers/VentaController.php', $.param(datos), function (response) {
            if (response.trim() === 'ok') {
                Swal.fire('¡Éxito!', 'Venta actualizada correctamente ✅', 'success');
                $('#formulario-venta').html('').hide();
                $('#contenedor-tabla-venta').show();
                recargarTablaVentas();
            } else {
                Swal.fire('Error', 'No se pudo actualizar: ' + response, 'error');
            }
        });
    });

    // Cancelar
    $(document).on('click', '#btn-cancelar-venta', function () {
        $('#formulario-venta').html('').hide();
        $('#contenedor-tabla-venta').show();
        recargarTablaVentas();
    });

    // Descargar PDF
    $(document).on('click', '#btn-descargar-pdf', function () {
        const idVenta = $(this).data('id');
        window.open(`../controllers/VentaController.php?accion=pdf_detalle&id=${idVenta}`, '_blank');
    });

    $(document).on('change', '#id_cliente', function () {
        if ($(this).val() === 'nuevo_cliente') {
            $.ajax({
                url: '../controllers/VentaController.php',
                type: 'POST',
                data: { accion: 'formulario_nuevo_cliente' },
                success: function (html) {
                    $('#modal-nuevo-cliente .modal-content').html(html);
                    const modal = new bootstrap.Modal(document.getElementById('modal-nuevo-cliente'));
                    modal.show();
                    $('#id_cliente').val('');
                },
                error: function () {
                    console.error("Error en la petición AJAX para cargar formulario nuevo cliente");
                    Swal.fire('Error', 'No se pudo cargar el formulario del cliente', 'error');
                    $('#id_cliente').val('');
                }
            });
        }
    });

    $(document).on('submit', '#form-nuevo-cliente', function (e) {
        e.preventDefault();

        const datosCliente = $(this).serialize() + '&accion=guardar_desde_venta';

        $.post('../controllers/ClienteController.php', datosCliente, function (response) {
            try {
                const res = JSON.parse(response);
                if (res.success) {
                    // Ocultar modal
                    const modalEl = document.getElementById('modal-nuevo-cliente');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    modalInstance.hide();

                    // Añadir nuevo cliente al select y seleccionarlo
                    $('#id_cliente').append(`<option value="${res.id}" selected>${res.nombre}</option>`);
                    Swal.fire('¡Éxito!', 'Cliente creado correctamente ✅', 'success');
                } else {
                    Swal.fire('Error', res.message || 'No se pudo guardar el cliente.', 'error');
                }
            } catch (err) {
                console.error("Error al parsear respuesta JSON:", err);
                Swal.fire('Error', 'Error inesperado: ' + response, 'error');
            }
        });
    });

    $('#modal-nuevo-cliente').on('hidden.bs.modal', function () {
        if ($('#id_cliente').val() === 'nuevo_cliente') {
            $('#id_cliente').val('');
        }
    });


    inicializarTablaVentas();
    asignarEventosVentas();

});
