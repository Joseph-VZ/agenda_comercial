<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreSubcategoria($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM subcategorias WHERE id = ?");
    $stmt->execute([$id]);
    $subcategoria = $stmt->fetch(PDO::FETCH_ASSOC);
    return $subcategoria ? $subcategoria['nombre'] : 'Sin subcategoría';
}
