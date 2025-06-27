<?php
class Municipio {
    private $conn;
    private $tabla = "municipios";

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

    public function crear($estado_id, $clave, $nombre) {
        try {

            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE (clave = :clave OR nombre = :nombre) AND estado_id = :estado_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':estado_id', $estado_id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'duplicado';
            }

            $sql = "INSERT INTO {$this->tabla} (estado_id, clave, nombre, activo) 
                    VALUES (:estado_id, :clave, :nombre, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':estado_id', $estado_id);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->execute();

            return 'ok';
        } catch (PDOException $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    public function actualizar($id, $estado_id, $clave, $nombre) {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} 
                    WHERE (clave = :clave OR nombre = :nombre) 
                    AND estado_id = :estado_id AND id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':estado_id', $estado_id);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'duplicado';
            }

            $sql = "UPDATE {$this->tabla} 
                    SET estado_id = :estado_id, clave = :clave, nombre = :nombre 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':estado_id', $estado_id);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':nombre', $nombre);
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
