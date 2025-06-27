<?php
require_once __DIR__ . '/../config/database.php';

function obtenerNombreUsuario($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    return $usuario ? $usuario['nombre'] : 'Sin asignar';
}

function obtenerNombreSubcategoria($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM subcategorias WHERE id = ?");
    $stmt->execute([$id]);
    $subcategoria = $stmt->fetch(PDO::FETCH_ASSOC);
    return $subcategoria ? $subcategoria['nombre'] : 'Sin subcategoría';
}

function obtenerNombreMunicipio($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM municipios WHERE id = ?");
    $stmt->execute([$id]);
    $municipio = $stmt->fetch(PDO::FETCH_ASSOC);
    return $municipio ? $municipio['nombre'] : 'Sin asignar';
}

function obtenerNombreMarca($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM marcas WHERE id = ?");
    $stmt->execute([$id]);
    $marca = $stmt->fetch(PDO::FETCH_ASSOC);
    return $marca ? $marca['nombre'] : 'Sin marca';
}

function obtenerNombreLocalidad($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM localidades WHERE id = ?");
    $stmt->execute([$id]);
    $localidad = $stmt->fetch(PDO::FETCH_ASSOC);
    return $localidad ? $localidad['nombre'] : 'Sin localidad';
}

function obtenerNombreEstado($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM estados WHERE id = ?");
    $stmt->execute([$id]);
    $estado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $estado ? $estado['nombre'] : 'Sin asignar';
}

function obtenerNombreCliente($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    return $cliente ? $cliente['nombre'] : 'Desconocido';
}

function obtenerNombreCategoria($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    return $categoria ? $categoria['nombre'] : 'Sin categoría';
}

function obtenerNombreProducto($db, $id) {
    $stmt = $db->prepare("SELECT nombre FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    return $producto ? $producto['nombre'] : 'Sin producto';
}
