<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions/functions.php';

class RVentasController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function mostrarReportesVentas() {
        require_once '../views/rventas.php';
    }

    public function opcionesFiltro($tipo) {
        $tablas = [
            'usuario' => 'usuarios',
            'cliente' => 'clientes',
            'producto' => 'productos'
        ];

        if (!isset($tablas[$tipo])) {
            http_response_code(400);
            echo json_encode(['error' => 'Tipo de filtro inválido']);
            return;
        }

        $tabla = $tablas[$tipo];
        $stmt = $this->db->query("SELECT id, nombre FROM $tabla ORDER BY nombre");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function generarReporte($tipo, $id, $fechaInicio, $fechaFin) {
        try {
            if (!$tipo || !$fechaInicio || !$fechaFin) {
                throw new Exception("Parámetros incompletos.");
            }

            $labels = [];
            $data = [];

            if ($tipo === 'producto') {
                $params = [$fechaInicio, $fechaFin];
                $filtroSQL = "WHERE v.fecha BETWEEN ? AND ?";

                if (!empty($id) && $id !== 'todos') {
                    $filtroSQL .= " AND dv.id_producto = ?";
                    $params[] = $id;
                }

                $sql = "
                    SELECT 
                        p.nombre AS agrupado,
                        SUM(dv.cantidad * dv.precio_unitario) AS total
                    FROM detalle_ventas dv
                    INNER JOIN ventas v ON dv.id_venta = v.id
                    INNER JOIN productos p ON dv.id_producto = p.id
                    $filtroSQL
                    GROUP BY dv.id_producto
                    ORDER BY total DESC
                ";

                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($resultados as $fila) {
                    $labels[] = $fila['agrupado'] ?? 'Desconocido';
                    $data[] = floatval($fila['total']);
                }

                echo json_encode([
                    'labels' => $labels,
                    'data' => $data,
                    'titulo' => 'Ventas por Producto'
                ]);
                return;
            }

            // Reportes para usuario, cliente y mes
            $params = [$fechaInicio, $fechaFin];
            $filtroSQL = "WHERE fecha BETWEEN ? AND ?";

            $cols = [
                'usuario' => ['col' => 'id_usuario', 'tabla' => 'usuarios'],
                'cliente' => ['col' => 'id_cliente', 'tabla' => 'clientes']
            ];

            if ($tipo === 'mes') {
                $selectAgrupado = "DATE(fecha) AS agrupado";
                $groupBy = "DATE(fecha)";
                $join = "";
            } elseif (isset($cols[$tipo])) {
                $col = $cols[$tipo]['col'];
                $tabla = $cols[$tipo]['tabla'];
                $selectAgrupado = "$tabla.nombre AS agrupado";
                $groupBy = "$col";
                $join = "LEFT JOIN $tabla ON ventas.$col = $tabla.id";

                if (!empty($id) && $id !== 'todos') {
                    $filtroSQL .= " AND $col = ?";
                    $params[] = $id;
                }
            } else {
                throw new Exception("Tipo inválido.");
            }

            $sql = "
                SELECT 
                    $selectAgrupado,
                    SUM(total) AS total
                FROM ventas
                $join
                $filtroSQL
                GROUP BY $groupBy
                ORDER BY $groupBy
            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultados as $fila) {
                $agrupado = $fila['agrupado'] ?? '';

                if ($tipo === 'mes') {
                    $labels[] = date('d M', strtotime($agrupado));
                } else {
                    $labels[] = $agrupado ?: 'Desconocido';
                }

                $data[] = floatval($fila['total']);
            }

            echo json_encode([
                'labels' => $labels,
                'data' => $data,
                'titulo' => 'Ventas por ' . ucfirst($tipo)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}


$database = new Database();
$db = $database->connect();
$controller = new RVentasController($db);


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'opcionesFiltro':
            $controller->opcionesFiltro($_GET['tipo'] ?? '');
            break;
        case 'generarReporte':
            $controller->generarReporte(
                $_GET['tipo'] ?? '',
                $_GET['id'] ?? '',
                $_GET['fechaInicio'] ?? '',
                $_GET['fechaFin'] ?? ''
            );
            break;
        case 'vista':
        default:
            $controller->mostrarReportesVentas();
            break;
    }
}
