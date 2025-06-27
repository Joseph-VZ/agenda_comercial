<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuario;

    public function __construct($db) {
        $this->usuario = new Usuario($db);
    }

    private function guardarFotografia($archivo) {
        if ($archivo['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
            $rutaDestino = __DIR__ . '/../uploads/usuarios/' . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                return $nombreArchivo;
            }
        }
        return null;
    }

    public function mostrarUsuarios() {
        $usuarios = $this->usuario->obtenerTodos();
        require_once '../views/usuarios.php';
    }

    public function mostrarUsuarioPorId($id) {
        $usuario = $this->usuario->obtenerPorId($id);
        require_once '../views/usuarios/form_editar_usuario.php';
    }

    public function guardarNuevoUsuario() {
        if (!isset($_POST['nombre'], $_POST['correo'], $_POST['password'], $_POST['rol'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $nombre = trim($_POST['nombre']);
        $correo = trim($_POST['correo']);
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        $rol = $_POST['rol'];

        $fotografia = null;
        if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
            $fotografia = $this->guardarFotografia($_FILES['fotografia']);
        }

        $resultado = $this->usuario->crearUsuario($nombre, $correo, $password, $rol, $fotografia);
        echo $resultado;
    }

    public function guardarEdicionUsuario() {
        if (!isset($_POST['id'], $_POST['nombre'], $_POST['correo'], $_POST['rol'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $correo = trim($_POST['correo']);
        $rol = $_POST['rol'];
        $password = !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_DEFAULT) : null;

        $fotografia = null;
        if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
            $fotografia = $this->guardarFotografia($_FILES['fotografia']);
        } else {
            $usuarioExistente = $this->usuario->obtenerPorId($id);
            $fotografia = $usuarioExistente['fotografia'];
        }

        $resultado = $this->usuario->actualizar($id, $nombre, $correo, $rol, $password, $fotografia);
        echo $resultado ? 'ok' : 'error: No se pudo actualizar';
    }

    public function mostrarTablaUsuarios() {
        $usuarios = $this->usuario->obtenerTodos();
        if (!is_array($usuarios)) {
            $usuarios = [];
        }
        require '../views/usuarios/tabla_usuarios.php';
    }

    public function desactivarUsuario() {
        $id = $_POST['id'] ?? 0;

        try {
            $resultado = $this->usuario->cambiarEstatus($id, 0); 
            echo $resultado ? 'ok' : 'error: No se pudo desactivar';
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }

    public function activarUsuario() {
        $id = $_POST['id'] ?? 0;

        try {
            $resultado = $this->usuario->cambiarEstatus($id, 1); 
            echo $resultado ? 'ok' : 'error: No se pudo activar';
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }

    public function verificarCorreo($db) {
        $correo = $_POST['correo'];
        $id = isset($_POST['id']) ? $_POST['id'] : null;

        try {
            if ($id) {
                $stmt = $db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE correo = :correo AND id != :id");
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':id', $id);
            } else {
                $stmt = $db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE correo = :correo");
                $stmt->bindParam(':correo', $correo);
            }

            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            echo ($resultado && $resultado['total'] > 0) ? 'existe' : 'libre';
        } catch (PDOException $e) {
            echo 'error: ' . $e->getMessage();
        }
    }
}


$database = new Database();
$db = $database->connect();
$usuarioController = new UsuarioController($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once '../views/usuarios/form_usuario.php';
            break;
        case 'guardar_nuevo':
            $usuarioController->guardarNuevoUsuario();
            break;
        case 'guardar_edicion':
            $usuarioController->guardarEdicionUsuario();
            break;
        case 'formulario_editar':
            $usuarioController->mostrarUsuarioPorId($_POST['id']);
            break;
        case 'tabla':
        case 'tabla_usuarios':
            $usuarioController->mostrarTablaUsuarios();
            break;
        case 'desactivar':
            $usuarioController->desactivarUsuario();
            break;
        case 'activar':
            $usuarioController->activarUsuario();
            break;
        case 'verificarCorreo':
            $usuarioController->verificarCorreo($db);
            break;
    }
}
?>
