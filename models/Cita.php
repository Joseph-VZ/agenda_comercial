<?php
class Cita {
    private $conn;
    private $tabla = "citas";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConexion() {
        return $this->conn;
    }

    public function obtenerTodos() {
        $sql = "SELECT c.*, 
                       cl.nombre AS nombre_cliente, 
                       u.nombre AS nombre_usuario
                FROM {$this->tabla} c
                LEFT JOIN clientes cl ON c.id_cliente = cl.id
                LEFT JOIN usuarios u ON c.id_usuario = u.id
                ORDER BY c.fecha DESC, c.hora_inicio ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($fecha, $hora_inicio, $hora_fin, $motivo, $id_cliente, $id_usuario, $estatus = 1, $google_event_id = null) {
        $sql = "INSERT INTO {$this->tabla} 
                (fecha, hora_inicio, hora_fin, motivo, id_cliente, id_usuario, estatus, google_event_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$fecha, $hora_inicio, $hora_fin, $motivo, $id_cliente, $id_usuario, $estatus, $google_event_id]);
    }

    public function actualizar($id, $fecha, $hora_inicio, $hora_fin, $motivo, $id_cliente, $id_usuario, $estatus) {
        $sql = "UPDATE {$this->tabla} 
                SET fecha = :fecha, hora_inicio = :hora_inicio, hora_fin = :hora_fin, 
                    motivo = :motivo, id_cliente = :id_cliente, id_usuario = :id_usuario, estatus = :estatus 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora_inicio', $hora_inicio);
        $stmt->bindParam(':hora_fin', $hora_fin);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':estatus', $estatus);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->tabla} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
