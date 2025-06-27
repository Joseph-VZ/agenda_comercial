<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../librerias/dompdf/autoload.inc.php';

class VentaController {
    private $venta;

    public function __construct($db) {
        $this->venta = new Venta($db);
    }

    public function mostrarVentas() {
        require_once __DIR__ . '/../models/Cliente.php';
        require_once __DIR__ . '/../models/Usuario.php';

        $clienteModel = new Cliente($this->venta->getConexion());
        $usuarioModel = new Usuario($this->venta->getConexion());

        $clientes = $clienteModel->obtenerTodos();
        $usuarios = $usuarioModel->obtenerTodos();

        require_once '../views/ventas.php';
    }

    public function obtenerVentaPorId($id) {
        return $this->venta->obtenerPorId($id);
    }

    public function mostrarVentaPorId($id) {
        $venta = $this->venta->obtenerPorId($id);

        require_once __DIR__ . '/../models/Cliente.php';
        require_once __DIR__ . '/../models/Usuario.php';
        require_once __DIR__ . '/../models/Producto.php';

        $clienteModel = new Cliente($this->venta->getConexion());
        $usuarioModel = new Usuario($this->venta->getConexion());
        $productoModel = new Producto($this->venta->getConexion());

        $clientes = $clienteModel->obtenerTodos();
        $usuarios = $usuarioModel->obtenerTodos();
        $productos = $productoModel->obtenerTodos();

        $productos_agregados = $this->obtenerProductosVenta($this->venta->getConexion(), $id);

        require_once '../views/ventas/form_editar_venta.php';

        echo "<script>
            const productosAgregados = " . json_encode($productos_agregados) . ";
            if (typeof renderizarTablaProductos === 'function') {
                renderizarTablaProductos();
            }
        </script>";
    }

    public function guardarNuevaVenta() {
        $fecha = $_POST['fecha'];
        $id_cliente = $_POST['id_cliente'];
        $id_usuario = $_POST['id_usuario'];
        $productos = json_decode($_POST['productos'], true);

        if (!is_array($productos) || empty($productos)) {
            echo 'Error: productos no válidos';
            return;
        }

        $productoModel = new Producto($this->venta->getConexion());
        $total = 0;

        foreach ($productos as $p) {
            $producto = $productoModel->obtenerPorId($p['id']);
            if (!$producto) {
                echo 'Error: producto no encontrado';
                return;
            }
            $total += $producto['precio'] * $p['cantidad'];
        }

        $resultado = $this->venta->crearConProductos($fecha, $total, $id_cliente, $productos, $id_usuario);
        echo $resultado ? 'ok' : 'Error al guardar';
    }

    public function guardarEdicionVenta() {
        $id = $_POST['id'];
        $fecha = $_POST['fecha'];
        $id_cliente = $_POST['id_cliente'];
        $id_usuario = $_POST['id_usuario'];
        $productos = json_decode($_POST['productos'], true);

        if (!is_array($productos) || empty($productos)) {
            echo 'Error: productos no válidos';
            return;
        }

        $productoModel = new Producto($this->venta->getConexion());
        $total = 0;

        foreach ($productos as $p) {
            $producto = $productoModel->obtenerPorId($p['id']);
            if (!$producto) {
                echo 'Error: producto no encontrado';
                return;
            }
            $total += $producto['precio'] * $p['cantidad'];
        }

        $resultado = $this->venta->actualizarConProductos($id, $fecha, $total, $id_cliente, $productos, $id_usuario);
        echo $resultado ? 'ok' : 'Error al actualizar';
    }

    public function mostrarTablaVentas() {
        $ventas = $this->venta->obtenerTodos() ?: [];

        require_once __DIR__ . '/../functions/f_cliente.php';
        require_once __DIR__ . '/../functions/f_usuario.php';

        foreach ($ventas as &$venta) {
            $venta['nombre_cliente'] = obtenerNombreCliente($this->venta->getConexion(), $venta['id_cliente']);
            $venta['nombre_usuario'] = obtenerNombreUsuario($this->venta->getConexion(), $venta['id_usuario']);
            $venta['acciones'] = '
                <button class="btn btn-sm btn-primary btn-editar-venta" data-id="' . $venta['id'] . '">Editar</button>
                <button class="btn btn-sm btn-danger btn-eliminar-venta" data-id="' . $venta['id'] . '">Eliminar</button>
                <button class="btn btn-info btn-sm btn-ver-detalles" data-id="' . $venta['id'] . '">
                    <i class="fas fa-eye"></i> Detalles
                </button>
            ';
        }

        echo json_encode(['data' => $ventas]);
    }


    public function eliminarVenta() {
        $id = $_POST['id'];
        $resultado = $this->venta->eliminar($id);
        echo ($resultado === true || $resultado === 1) ? 'ok' : $resultado;
    }

    public function obtenerProductosVenta($db, $venta_id) {
        $sql = "SELECT 
                    dv.id_producto AS id,
                    p.nombre,
                    dv.precio_unitario,
                    dv.cantidad,
                    dv.subtotal
                FROM detalle_ventas dv
                JOIN productos p ON p.id = dv.id_producto
                WHERE dv.id_venta = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$venta_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDatosEdicion($id)
        {
            require_once __DIR__ . '/../functions/f_cliente.php';
            require_once __DIR__ . '/../functions/f_usuario.php';
            require_once __DIR__ . '/../models/Producto.php'; 

            $venta = $this->venta->obtenerPorId($id);
            require_once __DIR__ . '/../models/Cliente.php';
            require_once __DIR__ . '/../models/Usuario.php';

            $clienteModel = new Cliente($this->venta->getConexion());
            $usuarioModel = new Usuario($this->venta->getConexion());

            $clientes = $clienteModel->obtenerTodos();
            $usuarios = $usuarioModel->obtenerTodos();


            $productoModel = new Producto($this->venta->getConexion());
            $productos = $productoModel->obtenerActivosIncluyendoVenta($id); 

            $productos_json = [];

            foreach ($venta['productos'] as $detalle) {
                $producto = array_filter($productos, fn($p) => $p['id'] == $detalle['id_producto']);
                $producto = reset($producto); 
                $productos_json[] = [
                    'id' => $detalle['id_producto'],
                    'nombre' => $producto ? $producto['nombre'] : 'Producto desconocido',
                    'precio' => isset($detalle['precio_unitario']) ? floatval($detalle['precio_unitario']) : floatval($producto['precio'] ?? 0),
                    'cantidad' => intval($detalle['cantidad']),
                ];
            }

            $venta['productos_json'] = json_encode($productos_json);

            return [
                'venta' => $venta,
                'clientes' => $clientes,
                'usuarios' => $usuarios,
                'productos' => $productos
            ];
        }

    public function generarPDFVenta($id) {
        $venta = $this->venta->obtenerPorId($id);
        $productos = $this->obtenerProductosVenta($this->venta->getConexion(), $id);

        if (!$venta) {
            echo "Error: Venta no encontrada";
            return;
        }

        require_once __DIR__ . '/../functions/f_cliente.php';
        require_once __DIR__ . '/../functions/f_usuario.php';

        $nombre_cliente = obtenerNombreCliente($this->venta->getConexion(), $venta['id_cliente']);
        $nombre_usuario = obtenerNombreUsuario($this->venta->getConexion(), $venta['id_usuario']);

        // Construir el HTML con datos reales
        $html = '
        <h1>Comprobante de Venta </h1>
        <p><strong>Cliente:</strong> ' . htmlspecialchars($nombre_cliente) . '</p>
        <p><strong>Vendedor:</strong> ' . htmlspecialchars($nombre_usuario) . '</p>
        <p><strong>Fecha:</strong> ' . htmlspecialchars($venta['fecha']) . '</p>
        <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th>Producto</th><th>Precio Unitario</th><th>Cantidad</th><th>Subtotal</th>
            </tr>
        </thead>
        <tbody>';
        
        $total = 0;
        foreach ($productos as $p) {
            $html .= '<tr>
                <td>' . htmlspecialchars($p['nombre']) . '</td>
                <td>$' . number_format($p['precio_unitario'], 2) . '</td>
                <td>' . intval($p['cantidad']) . '</td>
                <td>$' . number_format($p['subtotal'], 2) . '</td>
            </tr>';
            $total += $p['subtotal'];
        }

        $html .= '
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                <td><strong>$' . number_format($total, 2) . '</strong></td>
            </tr>
        </tbody>
        </table>
        ';

        // Generar PDF con Dompdf
        try {
            ob_end_clean();  // Limpia cualquier buffer previo

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Headers para PDF
            header("Content-type: application/pdf");
            header("Content-Disposition: inline; filename=\"comprobante_venta_{$venta['id']}.pdf\"");
            header("Cache-Control: private, max-age=0, must-revalidate");
            header("Pragma: public");

            $dompdf->stream("comprobante_venta_{$venta['id']}.pdf", ["Attachment" => false]);
            exit;

        } catch (Exception $e) {
            echo "Error generando PDF: " . $e->getMessage();
        }
    }


}

$database = new Database();
$db = $database->connect();
$ventaController = new VentaController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'formulario_nuevo':
            require_once __DIR__ . '/../models/Cliente.php';
            require_once __DIR__ . '/../models/Usuario.php';
            require_once __DIR__ . '/../models/Producto.php';

            $clientes = (new Cliente($db))->obtenerActivos();
            $usuarios = (new Usuario($db))->obtenerActivos();
            $productos = (new Producto($db))->obtenerActivos();

            require_once '../views/ventas/form_venta.php';
            break;

        case 'formulario_nuevo_cliente':
            require_once __DIR__ . '/../models/Usuario.php';
            $usuarios = (new Usuario($db))->obtenerActivos();
            require_once __DIR__ . '/../views/clientes/form_cliente.php';
            exit;

        case 'guardar_nuevo':
            $ventaController->guardarNuevaVenta();
            break;

        case 'guardar_edicion':
            $ventaController->guardarEdicionVenta();
            break;

        case 'formulario_editar':
            $datos = $ventaController->obtenerDatosEdicion($_POST['id']);
            extract($datos); 
            include '../views/ventas/form_editar_venta.php';
            break;

        case 'eliminar':
            $ventaController->eliminarVenta();
            break;

        case 'tabla':
            $ventaController->mostrarTablaVentas();
            break;

        case 'detalles':
            $id = $_POST['id'];

            $productos = $ventaController->obtenerProductosVenta($db, $id);
            $venta = $ventaController->obtenerVentaPorId($id);
            require_once __DIR__ . '/../functions/f_cliente.php';
            require_once __DIR__ . '/../functions/f_usuario.php';

            $nombre_cliente = obtenerNombreCliente($db, $venta['id_cliente']);
            $nombre_usuario = obtenerNombreUsuario($db, $venta['id_usuario']);

            echo "<p><strong>Cliente:</strong> " . htmlspecialchars($nombre_cliente) . "</p>";
            echo "<p><strong>Vendedor:</strong> " . htmlspecialchars($nombre_usuario) . "</p>";
            echo "<p><strong>Fecha:</strong> " . htmlspecialchars($venta['fecha']) . "</p>";

            if ($productos && count($productos) > 0) {
                $total = 0;
                echo '<table class="table table-sm table-bordered">';
                echo '<thead><tr><th>Producto</th><th>Precio Unitario</th><th>Cantidad</th><th>Subtotal</th></tr></thead><tbody>';
                foreach ($productos as $p) {
                    $total += $p['subtotal'];
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($p['nombre']) . '</td>';
                    echo '<td>$' . number_format($p['precio_unitario'], 2) . '</td>';
                    echo '<td>' . $p['cantidad'] . '</td>';
                    echo '<td>$' . number_format($p['subtotal'], 2) . '</td>';
                    echo '</tr>';
                }
                echo '<tr>';
                echo '<td colspan="3" class="text-end fw-bold">Total:</td>';
                echo '<td class="fw-bold">$' . number_format($total, 2) . '</td>';
                echo '</tr>';
                echo '</tbody></table>';
            } else {
                echo '<p class="text-muted">No se encontraron productos para esta venta.</p>';
            }
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        case 'pdf_detalle':
            if (!isset($_GET['id'])) {
                echo "Error: No se especificó id de venta";
                exit;
            }
            $id = intval($_GET['id']);
            $ventaController->generarPDFVenta($id);
            break;
    }
}
?>
