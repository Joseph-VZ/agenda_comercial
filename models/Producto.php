<?php
class Producto {
    private $conn;
    private $tabla = "productos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tabla}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tabla} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $descripcion, $precio, $stock, $estatus, $fotografia = null) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO {$this->tabla} 
                (nombre, descripcion, precio, stock, estatus, fotografia) 
                VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$nombre, $descripcion, $precio, $stock, $estatus, $fotografia]);
        } catch (PDOException $e) {
            echo 'Error en crear(): ' . $e->getMessage(); 
            return false;
        }
    }

    public function actualizar($id, $nombre, $descripcion, $precio, $stock, $estatus, $fotografia = null) {
        try {
            // Obtener la fotografía actual
            $productoActual = $this->obtenerPorId($id);
            $fotoActual = $productoActual['fotografia'] ?? null;

            // Si hay una nueva fotografía diferente a la actual, eliminar la anterior
            if (!empty($fotografia) && $fotoActual && $fotografia !== $fotoActual) {
                $rutaFoto = __DIR__ . '/../uploads/productos/' . $fotoActual;
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }

            if ($fotografia !== null) {
                $stmt = $this->conn->prepare("UPDATE {$this->tabla} SET 
                    nombre = ?, descripcion = ?, precio = ?, stock = ?, estatus = ?, fotografia = ? 
                    WHERE id = ?");
                return $stmt->execute([$nombre, $descripcion, $precio, $stock, $estatus, $fotografia, $id]);
            } else {
                $stmt = $this->conn->prepare("UPDATE {$this->tabla} SET 
                    nombre = ?, descripcion = ?, precio = ?, stock = ?, estatus = ? 
                    WHERE id = ?");
                return $stmt->execute([$nombre, $descripcion, $precio, $stock, $estatus, $id]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    
    public function desactivar($id) {
        try {
            $query = "UPDATE productos SET estatus = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }


    public function activar($id) {
        try {
            $query = "UPDATE productos SET estatus = 1 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function cambiarEstatus($id, $estatus) {
        $sql = "UPDATE productos SET estatus = :estatus WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerActivos() {
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE estatus = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerActivosIncluyendoVenta($id_venta) {
        $sql = "SELECT id_producto FROM detalle_ventas WHERE id_venta = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_venta]);
        $ids_usados = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id_producto');

        $placeholders = implode(',', array_fill(0, count($ids_usados), '?'));

        $query = "SELECT * FROM productos WHERE estatus = 1";
        if (!empty($ids_usados)) {
            $query .= " OR id IN ($placeholders)";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($ids_usados);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>
