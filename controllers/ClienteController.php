<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $cliente;

    public function __construct($db) {
        $this->cliente = new Cliente($db);
    }

    public function mostrarClientes() {
        $clientes = $this->cliente->obtenerTodos();
        require_once '../views/clientes.php';
    }

    public function mostrarClientePorId($id) {
        $cliente = $this->cliente->obtenerPorId($id);

        require_once __DIR__ . '/../models/Usuario.php';
        $usuarioModel = new Usuario($this->cliente->getConexion());
        $usuarios = $usuarioModel->obtenerTodos();

        require_once '../views/clientes/form_editar_cliente.php';
    }

    public function guardarNuevoCliente() {
        if (!isset($_POST['nombre'], $_POST['contacto'], $_POST['direccion'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $nombre = trim($_POST['nombre']);
        $contacto = trim($_POST['contacto']);
        $direccion = trim($_POST['direccion']);
        $id_usuario = $_POST['id_usuario'] ?? null;

        $resultado = $this->cliente->crear($nombre, $contacto, $direccion, $id_usuario);
        echo $resultado ? 'ok' : 'error: No se pudo guardar';
    }

    public function guardarEdicionCliente() {
        if (!isset($_POST['id'], $_POST['nombre'], $_POST['contacto'], $_POST['direccion'])) {
            echo 'error: Datos incompletos';
            return;
        }

        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $contacto = trim($_POST['contacto']);
        $direccion = trim($_POST['direccion']);
        $id_usuario = $_POST['id_usuario'] ?? null;

        $resultado = $this->cliente->actualizar($id, $nombre, $contacto, $direccion, $id_usuario);
        echo $resultado ? 'ok' : 'error: No se pudo actualizar';
    }

    public function mostrarTablaClientes() {
        $clientes = $this->cliente->obtenerTodos();
        if (!is_array($clientes)) {
            $clientes = [];
        }

        require_once __DIR__ . '/../functions/f_usuario.php';

        foreach ($clientes as $index => $cliente) {
            $clientes[$index]['nombre_usuario'] = obtenerNombreUsuario($this->cliente->getConexion(), $cliente['id_usuario']);
        }

        require '../views/clientes/tabla_clientes.php';
    }

    public function desactivarCliente() {
        $id = $_POST['id'] ?? 0;

        try {
            $resultado = $this->cliente->cambiarEstatus($id, 0);
            echo $resultado ? 'ok' : 'error: No se pudo desactivar';
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }

    public function activarCliente() {
        $id = $_POST['id'] ?? 0;

        try {
            $resultado = $this->cliente->cambiarEstatus($id, 1);
            echo $resultado ? 'ok' : 'error: No se pudo activar';
        } catch (PDOException $e) {
            echo 'error:' . $e->getMessage();
        }
    }
}

// Crear instancia del controlador
$database = new Database();
$db = $database->connect();
$clienteController = new ClienteController($db);

// Enrutamiento de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once __DIR__ . '/../models/Usuario.php';
            $usuarioModel = new Usuario($db);
            $usuarios = $usuarioModel->obtenerTodos();
            require_once '../views/clientes/form_cliente.php';
            break;

        case 'guardar_nuevo':
            $clienteController->guardarNuevoCliente();
            break;

        case 'guardar_edicion':
            $clienteController->guardarEdicionCliente();
            break;

        case 'formulario_editar':
            $clienteController->mostrarClientePorId($_POST['id']);
            break;

        case 'tabla':
        case 'tabla_clientes':
            $clienteController->mostrarTablaClientes();
            break;

        case 'desactivar':
            $clienteController->desactivarCliente();
            break;

        case 'activar':
            $clienteController->activarCliente();
            break;

        case 'guardar_desde_venta':
            $nombre = $_POST['nombre'];
            $contacto = $_POST['contacto'];
            $direccion = $_POST['direccion'];
            $id_usuario = $_POST['id_usuario'];

            $stmt = $db->prepare("INSERT INTO clientes (nombre, contacto, direccion, id_usuario, estatus) VALUES (?, ?, ?, ?, 1)");
            $ok = $stmt->execute([$nombre, $contacto, $direccion, $id_usuario]);

            if ($ok) {
                echo json_encode([
                    'success' => true,
                    'id' => $db->lastInsertId(),
                    'nombre' => $nombre
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos.']);
            }
            break;

        default:
            echo 'error: Acción no reconocida';
            break;
    }
}
?>
