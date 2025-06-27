<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Marca.php';

class MarcaController {
    private $marca;

    public function __construct($db) {
        $this->marca = new Marca($db);
    }

    public function mostrarMarcas() {
        $marcas = $this->marca->obtenerTodos();
        require_once '../views/marcas.php';
    }

    public function mostrarMarcaPorId($id) {
        $marca = $this->marca->obtenerPorId($id);
        require_once '../views/marcas/form_editar_marca.php';
    }

    public function guardarNuevoMarca() {
        if (!isset($_POST['nombre'])) {
            echo 'error: Falta el nombre';
            return;
        }

        $nombre = trim($_POST['nombre']);
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

        $resultado = $this->marca->crear($nombre, $descripcion);
        echo $resultado ? 'ok' : 'error: No se pudo guardar';
    }

    public function guardarEdicionMarca() {
        if (!isset($_POST['id'], $_POST['nombre'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

        $resultado = $this->marca->actualizar($id, $nombre, $descripcion);
        echo $resultado ? 'ok' : 'error: No se pudo actualizar';
    }

    public function mostrarTablaMarcas() {
        $marcas = $this->marca->obtenerTodos();
        if (!is_array($marcas)) {
            $marcas = [];
        }

        require '../views/marcas/tabla_marcas.php';
    }

    public function eliminarMarca() {
        $id = $_POST['id'];
        try {
            $resultado = $this->marca->eliminar($id);
            echo $resultado;
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }
}


$database = new Database();
$db = $database->connect();
$marcaController = new MarcaController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once '../views/marcas/form_marca.php';
            break;
        case 'guardar_nuevo':
            $marcaController->guardarNuevoMarca();
            break;
        case 'guardar_edicion':
            $marcaController->guardarEdicionMarca();
            break;
        case 'formulario_editar':
            $marcaController->mostrarMarcaPorId($_POST['id']);
            break;
        case 'eliminar':
            $marcaController->eliminarMarca();
            break;
        case 'tabla':
        case 'tabla_marcas':
            $marcaController->mostrarTablaMarcas();
            break;
    }
}
