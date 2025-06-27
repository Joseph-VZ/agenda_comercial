$(document).ready(function () {
    const tipoReporte = $('#tipoReporte');
    const filtroEspecifico = $('#filtroEspecifico');
    const fechaInicio = $('#fechaInicio');
    const fechaFin = $('#fechaFin');
    const btnGenerar = $('#btnGenerar');
    let chart;


    tipoReporte.change(function () {
        const tipo = $(this).val();
        filtroEspecifico.empty().prop('disabled', tipo === 'mes');

        if (tipo === 'usuario' || tipo === 'cliente' || tipo === 'producto') {
            $.getJSON(`../controllers/RVentasController.php?action=opcionesFiltro&tipo=${tipo}`, function (opciones) {
                filtroEspecifico.append('<option value="todos">Todos</option>');
                opciones.forEach(op => {
                    filtroEspecifico.append(`<option value="${op.id}">${op.nombre}</option>`);
                });
            }).fail(function () {
                Swal.fire('Error', 'No se pudieron cargar las opciones del filtro.', 'error');
            });
        }
    });

    // Botón generar
        btnGenerar.click(function () {
        const tipo = tipoReporte.val();
        const id = filtroEspecifico.val() || null;
        const fechaInicio = $('#fechaInicio').val();
        const fechaFin = $('#fechaFin').val();

        if (!tipo) {
            Swal.fire('Advertencia', 'Selecciona un tipo de reporte.', 'warning');
            return;
        }

        if (!fechaInicio || !fechaFin) {
            Swal.fire('Advertencia', 'Selecciona ambas fechas de inicio y fin.', 'warning');
            return;
        }

        if (fechaFin < fechaInicio) {
            Swal.fire('Advertencia', 'La fecha final no puede ser anterior a la fecha de inicio.', 'warning');
            return;
        }

        $.ajax({
            url: '../controllers/RVentasController.php',
            type: 'GET',
            data: {
                action: 'generarReporte',
                tipo,
                id,
                fechaInicio,
                fechaFin
            },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    Swal.fire('Error', response.error, 'error');
                    return;
                }

                if (chart) chart.destroy();

                const ctx = document.getElementById('graficoReporte').getContext('2d');
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: response.labels,
                        datasets: [{
                            label: response.titulo,
                            data: response.data,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Total ($)' }
                            },
                            x: {
                                title: { display: true, text: tipo === 'mes' ? 'Mes' : 'Detalle' }
                            }
                        }
                    }
                });
            },
            error: function (xhr) {
                let mensaje = 'No se pudo cargar el reporte.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    mensaje = xhr.responseJSON.error;
                }
                Swal.fire('Error', mensaje, 'error');
            }
        });
    });



    tipoReporte.trigger('change');
});
