<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    private $producto;

    public function __construct($db) {
        $this->producto = new Producto($db);
    }

    public function mostrarProductos() {
        $productos = $this->producto->obtenerTodos();
        require_once '../views/productos.php';
    }

    public function mostrarProductoPorId($id) {
        $producto = $this->producto->obtenerPorId($id);
        require_once '../views/productos/form_editar_producto.php';
    }

    private function guardarFotografia($archivo) {
        if ($archivo['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
            $rutaDestino = __DIR__ . '/../uploads/productos/' . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                return $nombreArchivo;
            }
        }
        return null;
    }

    public function guardarNuevoProducto() {
        if (!isset($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $precio = floatval($_POST['precio']);
        $stock = intval($_POST['stock']);
        $estatus = 1;

        $fotografia = null;
        if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
            $fotografia = $this->guardarFotografia($_FILES['fotografia']);
        }

        $resultado = $this->producto->crear($nombre, $descripcion, $precio, $stock, $estatus, $fotografia);
        echo $resultado ? 'ok' : 'error: No se pudo guardar';
    }

    public function guardarEdicionProducto() {
        if (!isset($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $precio = floatval($_POST['precio']);
        $stock = intval($_POST['stock']);

        $productoExistente = $this->producto->obtenerPorId($id);
        $estatus = $productoExistente['estatus'] ?? 1;
        $fotografia = $productoExistente['fotografia'];

        if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
            $fotografia = $this->guardarFotografia($_FILES['fotografia']);
        }

        $resultado = $this->producto->actualizar($id, $nombre, $descripcion, $precio, $stock, $estatus, $fotografia);
        echo $resultado ? 'ok' : 'error: No se pudo actualizar';
    }

    public function mostrarTablaProductos() {
        $productos = $this->producto->obtenerTodos();
        if (!is_array($productos)) {
            $productos = [];
        }

        require '../views/productos/tabla_productos.php';
    }

    public function desactivarProducto() {
        $id = $_POST['id'] ?? 0;

        try {
            $resultado = $this->producto->cambiarEstatus($id, 0);
            echo $resultado ? 'ok' : 'error: No se pudo desactivar';
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }

    public function activarProducto() {
        $id = $_POST['id'] ?? 0;

        try {
            $resultado = $this->producto->cambiarEstatus($id, 1);
            echo $resultado ? 'ok' : 'error: No se pudo activar';
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }
}


$database = new Database();
$db = $database->connect();
$productoController = new ProductoController($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once '../views/productos/form_producto.php';
            break;
        case 'guardar_nuevo':
            $productoController->guardarNuevoProducto();
            break;
        case 'guardar_edicion':
            $productoController->guardarEdicionProducto();
            break;
        case 'formulario_editar':
            $productoController->mostrarProductoPorId($_POST['id']);
            break;
        case 'tabla':
        case 'tabla_productos':
            $productoController->mostrarTablaProductos();
            break;
        case 'desactivar':
            $productoController->desactivarProducto();
            break;
        case 'activar':
            $productoController->activarProducto();
            break;
        default:
            echo 'error: Acción no reconocida';
            break;
    }
}
