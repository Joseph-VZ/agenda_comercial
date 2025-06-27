<?php
$rol = $_SESSION['usuario']['rol'] ?? '';

$permisos = [
    'admin' => ['usuarios', 'prospectos', 'clientes', 'productos', 'citas', 'ventas'],
    'gerente' => ['prospectos', 'clientes', 'productos', 'citas', 'ventas'],
    'asesor' => ['prospectos', 'clientes', 'citas']
];

// Función para comprobar si el módulo está permitido
function puedeVer($modulo, $rol, $permisos) {
    return in_array($modulo, $permisos[$rol] ?? []);
}
?>

<nav style="margin-bottom: 20px;">
    <?php if (puedeVer('usuarios', $rol, $permisos)): ?>
        <a href="index.php?modulo=usuarios">Usuarios</a> |
    <?php endif; ?>

    <?php if (puedeVer('prospectos', $rol, $permisos)): ?>
        <a href="index.php?modulo=prospectos">Prospectos</a> |
    <?php endif; ?>

    <?php if (puedeVer('clientes', $rol, $permisos)): ?>
        <a href="index.php?modulo=clientes">Clientes</a> |
    <?php endif; ?>

    <?php if (puedeVer('productos', $rol, $permisos)): ?>
        <a href="index.php?modulo=productos">Productos</a> |
    <?php endif; ?>

    <?php if (puedeVer('citas', $rol, $permisos)): ?>
        <a href="index.php?modulo=citas">Citas</a> |
    <?php endif; ?>

    <?php if (puedeVer('ventas', $rol, $permisos)): ?>
        <a href="index.php?modulo=ventas">Ventas</a> |
    <?php endif; ?>

    <a href="logout.php">Cerrar sesión</a>
</nav>
