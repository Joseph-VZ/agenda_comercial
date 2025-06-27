<?php
class Estado {
    private $conn;
    private $tabla = "estados";

    public function __construct($db) {
        $this->conn = $db;
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

    public function crear($clave, $nombre, $abrev) {
        try {

            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE clave = :clave OR nombre = :nombre";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'duplicado';
            }


            $sql = "INSERT INTO {$this->tabla} (clave, nombre, abrev, activo) VALUES (:clave, :nombre, :abrev, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':abrev', $abrev);
            $stmt->execute();

            return 'ok';
        } catch (PDOException $e) {
            return 'error: ' . $e->getMessage();
        }
    }

    public function actualizar($id, $clave, $nombre, $abrev) {
        try {

            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE (clave = :clave OR nombre = :nombre) AND id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'duplicado';
            }

            $sql = "UPDATE {$this->tabla} SET clave = :clave, nombre = :nombre, abrev = :abrev WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':abrev', $abrev);
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
