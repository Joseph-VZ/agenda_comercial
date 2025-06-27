<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreCliente($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    return $cliente ? $cliente['nombre'] : 'Desconocido';
}


