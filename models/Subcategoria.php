<?php
class Subcategoria {
    private $conn;
    private $tabla = "subcategorias";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConexion() {
        return $this->conn;
    }

    public function obtenerTodas() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tabla}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tabla} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $descripcion, $id_categoria) {
        try {
            // Verificar si ya existe una subcategoría con el mismo nombre en la misma categoría
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} 
                    WHERE nombre = :nombre AND id_categoria = :id_categoria";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id_categoria', $id_categoria);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'duplicado';
            }

            $sql = "INSERT INTO {$this->tabla} (nombre, descripcion, id_categoria) 
                    VALUES (:nombre, :descripcion, :id_categoria)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id_categoria', $id_categoria);
            $stmt->execute();

            return 'ok';
        } catch (PDOException $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    public function actualizar($id, $nombre, $descripcion, $id_categoria) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} 
                    WHERE nombre = :nombre AND id_categoria = :id_categoria AND id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id_categoria', $id_categoria);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'duplicado';
            }

            $sql = "UPDATE {$this->tabla} 
                    SET nombre = :nombre, descripcion = :descripcion, id_categoria = :id_categoria 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':id_categoria', $id_categoria);
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
            return "error:" . $e->getMessage();
        }
    }
}
?>
