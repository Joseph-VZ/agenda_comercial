<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Municipio.php';

class MunicipioController {
    private $municipio;
    private $db;

    public function __construct($db) {
         $this->db = $db;
        $this->municipio = new Municipio($db);
    }

    public function mostrarMunicipios() {
        $municipios = $this->municipio->obtenerTodos();
        require_once '../views/municipios.php';
    }

    public function mostrarMunicipioPorId($id) {
        $municipio = $this->municipio->obtenerPorId($id);

   
        require_once __DIR__ . '/../models/Estado.php';
        $estadoModel = new Estado($this->db);
        $estados = $estadoModel->obtenerTodos();

        require_once '../views/municipios/form_editar_municipio.php';
    }


    public function guardarNuevoMunicipio() {
        if (!isset($_POST['estado_id'], $_POST['clave'], $_POST['nombre'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $estado_id = $_POST['estado_id'];
        $clave = trim($_POST['clave']);
        $nombre = trim($_POST['nombre']);

        $resultado = $this->municipio->crear($estado_id, $clave, $nombre);
        echo $resultado ? 'ok' : 'error: No se pudo guardar';
    }

    public function guardarEdicionMunicipio() {
        if (!isset($_POST['id'], $_POST['estado_id'], $_POST['clave'], $_POST['nombre'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $estado_id = $_POST['estado_id'];
        $clave = trim($_POST['clave']);
        $nombre = trim($_POST['nombre']);

        $resultado = $this->municipio->actualizar($id, $estado_id, $clave, $nombre);
        echo $resultado ? 'ok' : 'error: No se pudo actualizar';
    }

    public function mostrarTablaMunicipios() {
        $municipios = $this->municipio->obtenerTodos();
        if (!is_array($municipios)) {
            $municipios = [];
        }

   
        require_once __DIR__ . '/../functions/f_estado.php';
        foreach ($municipios as &$municipio) {
            $municipio['estado_nombre'] = obtenerNombreEstado($this->municipio->getConexion(), $municipio['estado_id']);
        }

        require '../views/municipios/tabla_municipios.php';
    }

    public function eliminarMunicipio() {
        $id = $_POST['id'];
        try {
            $resultado = $this->municipio->eliminar($id);
            echo $resultado;
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }
}


$database = new Database();
$db = $database->connect();
$municipioController = new MunicipioController($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once '../views/municipios/form_municipio.php';
            break;
        case 'guardar_nuevo':
            $municipioController->guardarNuevoMunicipio();
            break;
        case 'guardar_edicion':
            $municipioController->guardarEdicionMunicipio();
            break;
        case 'formulario_editar':
            $municipioController->mostrarMunicipioPorId($_POST['id']);
            break;
        case 'eliminar':
            $municipioController->eliminarMunicipio();
            break;
        case 'tabla':
        case 'tabla_municipios':
            $municipioController->mostrarTablaMunicipios();
            break;
    }
}
