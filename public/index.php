<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../views/login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol'];

$permisos = [
    'admin' => ['inicio', 'usuarios', 'prospectos', 'clientes', 'productos', 'citas', 'ventas', 'catalogos', 'reportes'],
    'gerente' => ['inicio', 'prospectos', 'clientes', 'productos', 'citas', 'ventas', 'catalogos', 'reportes'],
    'asesor' => ['inicio', 'prospectos', 'clientes', 'citas', 'reportes'],
];

$modulosPermitidos = $permisos[$rol] ?? [];

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agenda Comercial</title>

  <!-- FontAwesome y Estilos -->
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../template/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap -->
  <script src="../template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../template/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../template/js/sb-admin-2.min.js"></script>

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <!-- Botones de Exportación -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

  <!-- FullCalendar -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

   <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="position: fixed; top: 0; left: 0; height: 100vh; overflow-y: auto; z-index: 1000;">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-address-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Agenda Comercial</div>
            </a>

            <hr class="sidebar-divider my-0">
            <?php if (in_array('inicio', $modulosPermitidos)): ?>
                <li class="nav-item"><a class="nav-link modulo-link" href="#" data-modulo="inicio"><i class="fas fa-home"></i><span>Inicio</span></a></li>
                <?php endif; ?>

                <?php if (in_array('usuarios', $modulosPermitidos)): ?>
                <li class="nav-item"><a class="nav-link modulo-link" href="#" data-modulo="usuarios"><i class="fas fa-user"></i><span>Usuarios</span></a></li>
                <?php endif; ?>

                <?php if (in_array('prospectos', $modulosPermitidos)): ?>
                <li class="nav-item"><a class="nav-link modulo-link" href="#" data-modulo="prospectos"><i class="fas fa-user-plus"></i><span>Prospectos</span></a></li>
                <?php endif; ?>

                <?php if (in_array('clientes', $modulosPermitidos)): ?>
                <li class="nav-item"><a class="nav-link modulo-link" href="#" data-modulo="clientes"><i class="fas fa-users"></i><span>Clientes</span></a></li>
                <?php endif; ?>

                <?php if (in_array('productos', $modulosPermitidos)): ?>
                <li class="nav-item"><a class="nav-link modulo-link" href="#" data-modulo="productos"><i class="fas fa-box"></i><span>Productos</span></a></li>
                <?php endif; ?>

                <?php if (in_array('citas', $modulosPermitidos)): ?>
                <li class="nav-item"><a class="nav-link modulo-link" href="#" data-modulo="citas"><i class="fas fa-calendar-alt"></i><span>Citas</span></a></li>
                <?php endif; ?>

                <?php if (in_array('ventas', $modulosPermitidos)): ?>
                <li class="nav-item"><a class="nav-link modulo-link" href="#" data-modulo="ventas"><i class="fas fa-shopping-cart"></i><span>Ventas</span></a></li>
                <?php endif; ?>

                <?php if (in_array('catalogos', $modulosPermitidos)): ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCatalogos" aria-expanded="false" aria-controls="collapseCatalogos">
                        <i class="fas fa-folder"></i>
                        <span>Catálogos</span>
                    </a>
                    <div id="collapseCatalogos" class="collapse" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item modulo-link" href="#" data-modulo="estados">Estados</a>
                            <a class="collapse-item modulo-link" href="#" data-modulo="municipios">Municipios</a>
                            <a class="collapse-item modulo-link" href="#" data-modulo="localidades">Localidades</a>
                            <a class="collapse-item modulo-link" href="#" data-modulo="zonas">Zonas</a>
                            <a class="collapse-item modulo-link" href="#" data-modulo="marcas">Marcas</a>
                            <a class="collapse-item modulo-link" href="#" data-modulo="categorias">Categorías</a>
                            <a class="collapse-item modulo-link" href="#" data-modulo="subcategorias">Subcategorías</a>
                        </div>
                    </div>
                </li>
                <?php endif; ?>

                 <?php if (in_array('reportes', $modulosPermitidos)): ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReportes" aria-expanded="false" aria-controls="collapseReportes">
                        <i class="fas fa-folder"></i>
                        <span>Reportes</span>
                    </a>
                    <div id="collapseReportes" class="collapse" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item modulo-link" href="#" data-modulo="reporte_ventas">Ventas</a>
                        </div>
                    </div>
                </li>
                <?php endif; ?>

            <hr class="sidebar-divider">

            <div class="text-center text-white small mb-3">
                <strong><?= $usuario['nombre'] ?></strong><br>
                <em><?= ucfirst($usuario['rol']) ?></em>
            </div>

            <li class="nav-item"><a class="nav-link" href="../views/logout.php"><i class="fas fa-sign-out-alt"></i><span>Cerrar Sesión</span></a></li>
        </ul>

        <!-- Contenido principal -->
        <div id="content-wrapper" class="d-flex flex-column" style="margin-left: 250px;">
            <div id="content" class="container mt-4">
                <div id="contenido-modulo">
                    <!-- Aquí se cargará el contenido dinámico con AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        function cargarModulo(modulo) {
            $.get("cargar_modulo.php?modulo=" + modulo, function(data) {
                $("#contenido-modulo").html(data);
            });
        }

        $(".modulo-link").click(function(e) {
            e.preventDefault();
            const modulo = $(this).data("modulo");
            cargarModulo(modulo);
        });


        cargarModulo("inicio");
    });
    </script>

    
</body>
</html>
