<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$db = $database->connect();

$modulo = $_GET['modulo'] ?? 'inicio';
$rol = $_SESSION['usuario']['rol'] ?? '';

$permisos = [
    'admin' => ['inicio', 'usuarios', 'prospectos', 'clientes', 'productos', 'citas', 'ventas', 'catalogos', 'reportes'],
    'gerente' => ['inicio', 'prospectos', 'clientes', 'productos', 'citas', 'ventas', 'catalogos', 'reportes'],
    'asesor' => ['inicio', 'prospectos', 'clientes', 'citas', 'reportes'],
];

$submodulosCatalogos = ['estados', 'municipios', 'localidades', 'marcas', 'categorias', 'subcategorias', 'zonas'];
$submodulosReportes = ['reporte_ventas'];

$permitido = in_array($modulo, $permisos[$rol] ?? []) ||
    (in_array('catalogos', $permisos[$rol] ?? []) && in_array($modulo, $submodulosCatalogos)) ||
    (in_array('reportes', $permisos[$rol] ?? []) && in_array($modulo, $submodulosReportes));


if (!$permitido) {
    echo "<h3 class='text-danger'>No tienes permiso para acceder a este módulo.</h3>";
    exit;
}


switch ($modulo) {
    case 'inicio':
        include "../views/inicio.php";
        break;
    case 'usuarios':
        require_once '../controllers/UsuarioController.php';
        $controller = new UsuarioController($db);
        $controller->mostrarUsuarios();
        break;
    case 'clientes':
        require_once '../controllers/ClienteController.php';
        $controller = new ClienteController($db);
        $controller->mostrarClientes();
        break;
    case 'prospectos':
        require_once '../controllers/ProspectoController.php';
        $controller = new ProspectoController($db);
        $controller->mostrarProspectos();
        break;
    case 'productos':
        require_once '../controllers/ProductoController.php';
        $controller = new ProductoController($db);
        $controller->mostrarProductos();
        break;
    case 'citas':
        require_once '../controllers/CitaController.php';
        $controller = new CitaController($db);
        $controller->mostrarCitas();
        break;
    case 'ventas':
        require_once '../controllers/VentaController.php';
        $controller = new VentaController($db);
        $controller->mostrarVentas();
        break;
    case 'estados':
        require_once '../controllers/EstadoController.php';
        $controller = new EstadoController($db);
        $controller->mostrarEstados();
        break;
    case 'municipios':
        require_once '../controllers/MunicipioController.php';
        $controller = new MunicipioController($db);
        $controller->mostrarMunicipios();
        break;
    case 'localidades':
        require_once '../controllers/LocalidadController.php';
        $controller = new LocalidadController($db);
        $controller->mostrarLocalidades();
        break;
    case 'marcas':
        require_once '../controllers/MarcaController.php';
        $controller = new MarcaController($db);
        $controller->mostrarMarcas();
        break;
    case 'categorias':
        require_once '../controllers/CategoriaController.php';
        $controller = new CategoriaController($db);
        $controller->mostrarCategorias();
        break;
    case 'subcategorias':
        require_once '../controllers/SubcategoriaController.php';
        $controller = new SubcategoriaController($db);
        $controller->mostrarSubcategorias();
        break;
    case 'reporte_ventas':
        require_once '../controllers/RVentasController.php'; 
        $controller = new RVentasController($db);            
        $controller->mostrarReportesVentas();
        break;
    case 'zonas':
        require_once '../controllers/ZonaController.php'; 
        $controller = new ZonaController($db);            
        $controller->mostrarZonas();
        break;

    default:
        echo "Módulo no encontrado.";
        exit;
}


$jsFile = "{$modulo}.js";
$jsPath = __DIR__ . "/../js/{$modulo}/$jsFile"; 
$jsPublicPath = "../js/{$modulo}/$jsFile"; 

if (file_exists($jsPath)) {
    echo "<script src='$jsPublicPath'></script>";
}

?>
