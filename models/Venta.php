<?php
class Venta {
    private $conn;
    private $tabla = "ventas";
    private $tablaDetalle = "detalle_ventas"; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConexion() {
        return $this->conn;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $venta = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$venta) return null;


        $sqlDetalle = "SELECT dv.*, p.nombre AS nombre_producto 
                       FROM {$this->tablaDetalle} dv
                       LEFT JOIN productos p ON dv.id_producto = p.id
                       WHERE dv.id_venta = ?";
        $stmtDetalle = $this->conn->prepare($sqlDetalle);
        $stmtDetalle->execute([$id]);
        $venta['productos'] = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

        return $venta;
    }

    public function crearConProductos($fecha, $total, $id_cliente, $productos, $id_usuario) {
        try {
            $this->conn->beginTransaction();


            $sql = "INSERT INTO {$this->tabla} (fecha, total, id_cliente, id_usuario) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$fecha, $total, $id_cliente, $id_usuario]);
            $id_venta = $this->conn->lastInsertId();

 
            $sqlDetalle = "INSERT INTO {$this->tablaDetalle} 
                           (id_venta, id_producto, cantidad, precio_unitario, subtotal)
                           VALUES (?, ?, ?, ?, ?)";
            $stmtDetalle = $this->conn->prepare($sqlDetalle);

            foreach ($productos as $p) {
                $id_producto = $p['id'];
                $cantidad = $p['cantidad'];


                $stmtPrecio = $this->conn->prepare("SELECT precio FROM productos WHERE id = ?");
                $stmtPrecio->execute([$id_producto]);
                $precio = $stmtPrecio->fetchColumn();

                $subtotal = $precio * $cantidad;
                $stmtDetalle->execute([$id_venta, $id_producto, $cantidad, $precio, $subtotal]);
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return "Error SQL: " . $e->getMessage();
        }
    }

    public function actualizarConProductos($id, $fecha, $total, $id_cliente, $productos, $id_usuario) {
        try {
            $this->conn->beginTransaction();


            $sql = "UPDATE {$this->tabla} 
                    SET fecha = ?, total = ?, id_cliente = ?, id_usuario = ? 
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$fecha, $total, $id_cliente, $id_usuario, $id]);


            $stmtEliminar = $this->conn->prepare("DELETE FROM {$this->tablaDetalle} WHERE id_venta = ?");
            $stmtEliminar->execute([$id]);


            $sqlDetalle = "INSERT INTO {$this->tablaDetalle} 
                           (id_venta, id_producto, cantidad, precio_unitario, subtotal)
                           VALUES (?, ?, ?, ?, ?)";
            $stmtDetalle = $this->conn->prepare($sqlDetalle);

            foreach ($productos as $p) {
                $id_producto = $p['id'];
                $cantidad = $p['cantidad'];

                $stmtPrecio = $this->conn->prepare("SELECT precio FROM productos WHERE id = ?");
                $stmtPrecio->execute([$id_producto]);
                $precio = $stmtPrecio->fetchColumn();

                $subtotal = $precio * $cantidad;
                $stmtDetalle->execute([$id, $id_producto, $cantidad, $precio, $subtotal]);
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return "Error SQL: " . $e->getMessage();
        }
    }

    public function eliminar($id) {
        try {
            $this->conn->beginTransaction();

            $stmtDetalle = $this->conn->prepare("DELETE FROM {$this->tablaDetalle} WHERE id_venta = ?");
            $stmtDetalle->execute([$id]);

            $stmtVenta = $this->conn->prepare("DELETE FROM {$this->tabla} WHERE id = ?");
            $stmtVenta->execute([$id]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return "Error SQL: " . $e->getMessage();
        }
    }
}
?>
