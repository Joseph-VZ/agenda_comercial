<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Zona.php';

class ZonaController {
    private $zona;

    public function __construct($db) {
        $this->zona = new Zona($db);
    }

    public function mostrarZonas() {
        $categorias = $this->zona->obtenerTodos();
        require_once '../views/zonas.php';
    }

    public function mostrarZonaPorId($id) {
        $zona = $this->zona->obtenerPorId($id);
        require_once '../views/zonas/form_editar_zona.php';
    }

    public function guardarNuevoZona() {
        if (!isset($_POST['nombre'])) {
            echo 'error: Falta el nombre';
            return;
        }

        $nombre = trim($_POST['nombre']);
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

        $resultado = $this->zona->crear($nombre, $descripcion);
        echo $resultado === 'ok' ? 'ok' : $resultado;
    }

    public function guardarEdicionZona() {
        if (!isset($_POST['id'], $_POST['nombre'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

        $resultado = $this->zona->actualizar($id, $nombre, $descripcion);
        echo $resultado === true ? 'ok' : ($resultado === 'duplicado' ? 'duplicado' : 'error: No se pudo actualizar');
    }

    public function mostrarTablaZonas() {
        $zonas = $this->zona->obtenerTodos();
        if (!is_array($zonas)) {
            $zonas = [];
        }

        require '../views/zonas/tabla_zonas.php';
    }

    public function eliminarZona() {
        $id = $_POST['id'];
        $resultado = $this->zona->eliminar($id);
        echo $resultado === 'ok' ? 'ok' : $resultado;
    }
}


$database = new Database();
$db = $database->connect();
$zonaController = new ZonaController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once '../views/zonas/form_zona.php';
            break;
        case 'guardar_nuevo':
            $zonaController->guardarNuevoZona();
            break;
        case 'guardar_edicion':
            $zonaController->guardarEdicionZona();
            break;
        case 'formulario_editar':
            $zonaController->mostrarZonaPorId($_POST['id']);
            break;
        case 'eliminar':
            $zonaController->eliminarZona();
            break;
        case 'tabla':
        case 'tabla_categorias':
            $zonaController->mostrarTablaZonas();
            break;
    }
}
