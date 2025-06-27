<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Estado.php';

class EstadoController {
    private $estado;

    public function __construct($db) {
        $this->estado = new Estado($db);
    }

    public function mostrarEstados() {
        $estados = $this->estado->obtenerTodos();
        require_once '../views/estados.php';
    }

    public function mostrarEstadoPorId($id) {
        $estado = $this->estado->obtenerPorId($id);
        require_once '../views/estados/form_editar_estado.php';
    }

    public function guardarNuevoEstado() {
        if (!isset($_POST['clave'], $_POST['nombre'], $_POST['abrev'])) {
            echo 'error: Datos incompletos';
            return;
        }
        $clave = trim($_POST['clave']);
        $nombre = trim($_POST['nombre']);
        $abrev = trim($_POST['abrev']);

        $resultado = $this->estado->crear($clave, $nombre, $abrev);
        echo $resultado ? 'ok' : 'error: No se pudo guardar';
    }


    public function guardarEdicionEstado() {
        if (!isset($_POST['id'], $_POST['clave'], $_POST['nombre'], $_POST['abrev'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $clave = trim($_POST['clave']);
        $nombre = trim($_POST['nombre']);
        $abrev = trim($_POST['abrev']);

        $resultado = $this->estado->actualizar($id, $clave, $nombre, $abrev);
        echo $resultado ? 'ok' : 'error: No se pudo actualizar';
    }


    public function mostrarTablaEstados() {
        $estados = $this->estado->obtenerTodos();
        if (!is_array($estados)) {
            $estados = [];
        }
        require '../views/estados/tabla_estados.php';
    }

    public function eliminarEstado() {
        $id = $_POST['id'];
        try {
            $resultado = $this->estado->eliminar($id);
            echo $resultado;
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }
}


$database = new Database();
$db = $database->connect();
$estadoController = new EstadoController($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once '../views/estados/form_estado.php';
            break;
        case 'guardar_nuevo':
            $estadoController->guardarNuevoEstado();
            break;
        case 'guardar_edicion':
            $estadoController->guardarEdicionEstado();
            break;
        case 'formulario_editar':
            $estadoController->mostrarEstadoPorId($_POST['id']);
            break;
        case 'eliminar':
            $estadoController->eliminarEstado();
            break;
        case 'tabla':
        case 'tabla_estados':
            $estadoController->mostrarTablaEstados();
            break;
    }
}
?>
