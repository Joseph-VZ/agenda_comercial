<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreMarca($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM marcas WHERE id = ?");
    $stmt->execute([$id]);
    $marca = $stmt->fetch(PDO::FETCH_ASSOC);
    return $marca ? $marca['nombre'] : 'Sin marca';
}
