<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Prospecto.php';

class ProspectoController {
    private $prospecto;

    public function __construct($db) {
        $this->prospecto = new Prospecto($db);
    }

    public function mostrarProspectos() {
        $prospectos = $this->prospecto->obtenerTodos();
        require_once '../views/prospectos.php';
    }

    public function mostrarFormularioEditar($id) {
        $prospecto = $this->prospecto->obtenerPorId($id);
        require '../views/prospectos/form_editar_prospecto.php';
    }

    public function guardarNuevo() {
        $nombre = $_POST['nombre'];
        $contacto = $_POST['contacto'];
        $estado = $_POST['estado_interes']; 
        $fecha = date('Y-m-d'); 

        echo $this->prospecto->crear($nombre, $contacto, $estado, $fecha) ? 'ok' : 'error';
    }

    public function guardarEdicion() {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $contacto = $_POST['contacto'];
        $estado = $_POST['estado_interes']; 
        $fecha = date('Y-m-d'); 

        echo $this->prospecto->actualizar($id, $nombre, $contacto, $estado, $fecha) ? 'ok' : 'error';
    }

    public function eliminar() {
        $id = $_POST['id'];
        echo $this->prospecto->eliminar($id) ? 'ok' : 'error';
    }

    public function mostrarFormularioNuevo() {
        require '../views/prospectos/form_prospecto.php';
    }

    public function mostrarTabla() {
        $prospectos = $this->prospecto->obtenerTodos();
        require '../views/prospectos/tabla_prospectos.php';
    }
}

$database = new Database();
$db = $database->connect();
$controller = new ProspectoController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            $controller->mostrarFormularioNuevo();
            break;
        case 'guardar_nuevo':
            $controller->guardarNuevo();
            break;
        case 'formulario_editar':
            $controller->mostrarFormularioEditar($_POST['id']);
            break;
        case 'guardar_edicion':
            $controller->guardarEdicion();
            break;
        case 'eliminar':
            $controller->eliminar();
            break;
        case 'tabla':
            $controller->mostrarTabla();
            break;
    }
}
?>
