<?php
class Prospecto {
    private $conn;
    private $tabla = "prospectos";

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

    public function crear($nombre, $contacto, $estado_interes) {
        try {
            $sql = "INSERT INTO {$this->tabla} (nombre, contacto, estado_interes, created_at, updated_at) 
                    VALUES (:nombre, :contacto, :estado_interes, NOW(), NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':contacto', $contacto);
            $stmt->bindParam(':estado_interes', $estado_interes);
            $stmt->execute();
            return 1;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }


    public function actualizar($id, $nombre, $contacto, $estado_interes) {
        $sql = "UPDATE {$this->tabla} 
                SET nombre = ?, contacto = ?, estado_interes = ?, updated_at = NOW() 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $contacto, $estado_interes, $id]);
    }


    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->tabla} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>


