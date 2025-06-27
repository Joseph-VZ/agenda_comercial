<?php
class Cliente {
    private $conn;
    private $tabla = "clientes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getConexion() {
        return $this->conn;
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

    public function crear($nombre, $contacto, $direccion, $correo, $password, $id_usuario) {
        try {
            // Verificar si el correo ya existe
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE correo = :correo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'correo_duplicado';
            }

            // Insertar cliente
            $sql = "INSERT INTO {$this->tabla} 
                    (nombre, contacto, direccion, correo, password, id_usuario, estatus) 
                    VALUES (:nombre, :contacto, :direccion, :correo, :password, :id_usuario, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':contacto', $contacto);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function actualizar($id, $nombre, $contacto, $direccion, $correo, $password, $id_usuario) {
        try {
            // Verificar si el correo ya está en uso por otro cliente
            $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE correo = :correo AND id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'correo_duplicado';
            }

            // Actualizar
            if (!empty($password)) {
                $stmt = $this->conn->prepare("UPDATE {$this->tabla} 
                    SET nombre = ?, contacto = ?, direccion = ?, correo = ?, password = ?, id_usuario = ? 
                    WHERE id = ?");
                return $stmt->execute([$nombre, $contacto, $direccion, $correo, $password, $id_usuario, $id]);
            } else {
                $stmt = $this->conn->prepare("UPDATE {$this->tabla} 
                    SET nombre = ?, contacto = ?, direccion = ?, correo = ?, id_usuario = ? 
                    WHERE id = ?");
                return $stmt->execute([$nombre, $contacto, $direccion, $correo, $id_usuario, $id]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function cambiarEstatus($id, $estatus) {
        $stmt = $this->conn->prepare("UPDATE {$this->tabla} SET estatus = :estatus WHERE id = :id");
        $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPorCorreo($correo) {
        $query = "SELECT * FROM {$this->tabla} WHERE correo = :correo AND estatus = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function correoExiste($correo) {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE correo = :correo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado && $resultado['total'] > 0;
    }

    public function obtenerActivos() {
        $sql = "SELECT * FROM {$this->tabla} WHERE estatus = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerActivosIncluyendo($id_incluir = null) {
        if ($id_incluir) {
            $sql = "SELECT * FROM {$this->tabla} WHERE estatus = 1 OR id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_incluir]);
        } else {
            $sql = "SELECT * FROM {$this->tabla} WHERE estatus = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
