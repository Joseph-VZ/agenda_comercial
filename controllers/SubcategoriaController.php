<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Subcategoria.php';

class SubcategoriaController {
    private $subcategoria;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->subcategoria = new Subcategoria($db);
    }

    public function mostrarSubcategorias() {
        $subcategorias = $this->subcategoria->obtenerTodas();
        require_once '../views/subcategorias.php';
    }

    public function mostrarSubcategoriaPorId($id) {
        $subcategoria = $this->subcategoria->obtenerPorId($id);

        require_once __DIR__ . '/../models/Categoria.php';
        $categoriaModel = new Categoria($this->db);
        $categorias = $categoriaModel->obtenerTodos();

        require_once '../views/subcategorias/form_editar_subcategoria.php';
    }

    public function guardarNuevaSubcategoria() {
        if (!isset($_POST['nombre'], $_POST['descripcion'], $_POST['id_categoria'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $id_categoria = $_POST['id_categoria'];

        $resultado = $this->subcategoria->crear($nombre, $descripcion, $id_categoria);
        echo $resultado ? 'ok' : 'error: No se pudo guardar';
    }

    public function guardarEdicionSubcategoria() {
        if (!isset($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['id_categoria'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $id_categoria = $_POST['id_categoria'];

        $resultado = $this->subcategoria->actualizar($id, $nombre, $descripcion, $id_categoria);
        echo $resultado ? 'ok' : 'error: No se pudo actualizar';
    }

    public function mostrarTablaSubcategorias() {
        $subcategorias = $this->subcategoria->obtenerTodas();
        if (!is_array($subcategorias)) {
            $subcategorias = [];
        }

        require_once __DIR__ . '/../functions/f_categoria.php';
        foreach ($subcategorias as &$subcat) {
            $subcat['categoria_nombre'] = obtenerNombreCategoria($this->subcategoria->getConexion(), $subcat['id_categoria']);
        }

        require '../views/subcategorias/tabla_subcategorias.php';
    }

    public function eliminarSubcategoria() {
        $id = $_POST['id'];
        try {
            $resultado = $this->subcategoria->eliminar($id);
            echo $resultado;
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }
}


$database = new Database();
$db = $database->connect();
$subcategoriaController = new SubcategoriaController($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once __DIR__ . '/../models/Categoria.php';
            $categoriaModel = new Categoria($db);
            $categorias = $categoriaModel->obtenerTodos();

            require_once '../views/subcategorias/form_subcategoria.php';
            break;
        case 'guardar_nuevo':
            $subcategoriaController->guardarNuevaSubcategoria();
            break;
        case 'guardar_edicion':
            $subcategoriaController->guardarEdicionSubcategoria();
            break;
        case 'formulario_editar':
            $subcategoriaController->mostrarSubcategoriaPorId($_POST['id']);
            break;
        case 'eliminar':
            $subcategoriaController->eliminarSubcategoria();
            break;
        case 'tabla':
        case 'tabla_subcategorias':
            $subcategoriaController->mostrarTablaSubcategorias();
            break;
    }
}
