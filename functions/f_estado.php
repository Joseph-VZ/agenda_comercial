<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreEstado($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM estados WHERE id = ?");
    $stmt->execute([$id]);
    $estado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $estado ? $estado['nombre'] : 'Sin asignar';
}
