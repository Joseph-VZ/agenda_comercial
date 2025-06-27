<?php
class Marca {
    private $conn;
    private $tabla = "marcas";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConexion() {
        return $this->conn;
    }

    public function obtenerTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tabla} WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tabla} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $descripcion) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE nombre = :nombre";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'duplicado';
            }

            $sql = "INSERT INTO {$this->tabla} (nombre, descripcion, activo) 
                    VALUES (:nombre, :descripcion, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->execute();

            return 'ok';
        } catch (PDOException $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    public function actualizar($id, $nombre, $descripcion) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} 
                    WHERE nombre = :nombre AND id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'duplicado';
            }

            $sql = "UPDATE {$this->tabla} 
                    SET nombre = :nombre, descripcion = :descripcion 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $sql = "DELETE FROM {$this->tabla} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return "ok";
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $sql = "UPDATE {$this->tabla} SET activo = 0 WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                return "desactivado";
            } else {
                return "error:" . $e->getMessage();
            }
        }
    }

    public function activar($id) {
        try {
            $sql = "UPDATE {$this->tabla} SET activo = 1 WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
