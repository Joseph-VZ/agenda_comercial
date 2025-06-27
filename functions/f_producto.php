<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreProducto($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    return $producto ? $producto['nombre'] : 'Sin asignar';
}
