<?php
class Usuario {
    private $conn;
    private $tabla = "usuarios";

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

    public function crearUsuario($nombre, $correo, $password, $rol, $fotografia) {
        try {

            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE correo = :correo";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'correo_duplicado';
            }



            $sql = "INSERT INTO usuarios (nombre, correo, password, rol, fotografia) 
                    VALUES (:nombre, :correo, :password, :rol, :fotografia)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':fotografia', $fotografia);
            $stmt->execute();

            return 'ok';
        } catch (PDOException $e) {
            return 'error: ' . $e->getMessage();
        }
    }


       
    public function actualizar($id, $nombre, $correo, $rol, $password, $fotografia = null) {
        try {

            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE correo = :correo AND id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado && $resultado['total'] > 0) {
                return 'correo_duplicado';
            }


            $usuarioActual = $this->obtenerPorId($id);
            $fotoActual = $usuarioActual['fotografia'] ?? null;


            if (!empty($fotografia) && $fotoActual && $fotografia !== $fotoActual) {
                $rutaFoto = __DIR__ . '/../uploads/usuarios/' . $fotoActual;
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }

            if (!is_null($password) && $password !== '') {
                $stmt = $this->conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, password = ?, rol = ?, fotografia = ? WHERE id = ?");
                return $stmt->execute([$nombre, $correo, $password, $rol, $fotografia, $id]);
            } else {
                $stmt = $this->conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, rol = ?, fotografia = ? WHERE id = ?");
                return $stmt->execute([$nombre, $correo, $rol, $fotografia, $id]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function activar($id) {
        try {
            $query = "UPDATE usuarios SET estatus = 1 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function desactivar($id) {
        try {
            $query = "UPDATE usuarios SET estatus = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerPorCorreo($correo) {
        $query = "SELECT * FROM usuarios WHERE correo = :correo AND estatus = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function correoExiste($correo) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE correo = :correo";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado && $resultado['total'] > 0;
    }

    public function cambiarEstatus($id, $estatus) {
        $sql = "UPDATE usuarios SET estatus = :estatus WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':estatus', $estatus, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerActivos() {
        $sql = "SELECT * FROM usuarios WHERE estatus = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerActivosIncluyendo($id_incluir = null) {
        if ($id_incluir) {
            $sql = "SELECT * FROM usuarios WHERE estatus = 1 OR id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_incluir]);
        } else {
            $sql = "SELECT * FROM usuarios WHERE estatus = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>
