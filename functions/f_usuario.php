<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreUsuario($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    return $usuario ? $usuario['nombre'] : 'Sin asignar';
}

