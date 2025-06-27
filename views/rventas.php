<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Ventas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<?php require_once __DIR__ . '/../middleware/auth.php'; ?>

<div class="container py-4">
    <h1 class="text-center mb-4">Reporte de Ventas</h1>

    <!-- Filtros -->
    <div class="card p-4 shadow-sm mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="tipoReporte" class="form-label">Tipo de Reporte:</label>
                <select id="tipoReporte" class="form-select">
                    <option value="mes">Ventas por Mes</option>
                    <option value="usuario">Ventas por Usuario</option>
                    <option value="cliente">Ventas por Cliente</option>
                    <option value="producto">Ventas por Producto</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroEspecifico" class="form-label">Filtro:</label>
                <select id="filtroEspecifico" class="form-select" disabled></select>
            </div>
            <div class="col-md-3">
                <label for="fechaInicio" class="form-label">Fecha Inicio:</label>
                <input type="date" id="fechaInicio" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="fechaFin" class="form-label">Fecha Fin:</label>
                <input type="date" id="fechaFin" class="form-control">
            </div>
            <div class="col-12 d-grid d-md-flex justify-content-md-end mt-3">
                <button id="btnGenerar" class="btn btn-primary">
                    <i class="fas fa-chart-bar me-2"></i>Generar Reporte
                </button>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="card p-4 shadow-sm mb-4">
        <canvas id="graficoReporte" height="100"></canvas>
    </div>
</div>


<script src="../js/reporte_ventas/rventas.js"></script>

</body>
</html>
