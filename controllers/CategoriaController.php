<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Categoria.php';

class CategoriaController {
    private $categoria;

    public function __construct($db) {
        $this->categoria = new Categoria($db);
    }

    public function mostrarCategorias() {
        $categorias = $this->categoria->obtenerTodos();
        require_once '../views/categorias.php';
    }

    public function mostrarCategoriaPorId($id) {
        $categoria = $this->categoria->obtenerPorId($id);
        require_once '../views/categorias/form_editar_categoria.php';
    }

    public function guardarNuevoCategoria() {
        if (!isset($_POST['nombre'])) {
            echo 'error: Falta el nombre';
            return;
        }

        $nombre = trim($_POST['nombre']);
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

        $resultado = $this->categoria->crear($nombre, $descripcion);
        echo $resultado === 'ok' ? 'ok' : $resultado;
    }

    public function guardarEdicionCategoria() {
        if (!isset($_POST['id'], $_POST['nombre'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

        $resultado = $this->categoria->actualizar($id, $nombre, $descripcion);
        echo $resultado === true ? 'ok' : ($resultado === 'duplicado' ? 'duplicado' : 'error: No se pudo actualizar');
    }

    public function mostrarTablaCategorias() {
        $categorias = $this->categoria->obtenerTodos();
        if (!is_array($categorias)) {
            $categorias = [];
        }

        require '../views/categorias/tabla_categorias.php';
    }

    public function eliminarCategoria() {
        $id = $_POST['id'];
        $resultado = $this->categoria->eliminar($id);
        echo $resultado === 'ok' ? 'ok' : $resultado;
    }
}


$database = new Database();
$db = $database->connect();
$categoriaController = new CategoriaController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once '../views/categorias/form_categoria.php';
            break;
        case 'guardar_nuevo':
            $categoriaController->guardarNuevoCategoria();
            break;
        case 'guardar_edicion':
            $categoriaController->guardarEdicionCategoria();
            break;
        case 'formulario_editar':
            $categoriaController->mostrarCategoriaPorId($_POST['id']);
            break;
        case 'eliminar':
            $categoriaController->eliminarCategoria();
            break;
        case 'tabla':
        case 'tabla_categorias':
            $categoriaController->mostrarTablaCategorias();
            break;
    }
}
