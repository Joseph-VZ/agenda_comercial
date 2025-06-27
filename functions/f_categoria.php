<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreCategoria($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    return $categoria ? $categoria['nombre'] : 'Sin categoría';
}



