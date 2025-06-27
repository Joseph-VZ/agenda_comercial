<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreMunicipio($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM municipios WHERE id = ?");
    $stmt->execute([$id]);
    $municipio = $stmt->fetch(PDO::FETCH_ASSOC);
    return $municipio ? $municipio['nombre'] : 'Sin asignar';
}
