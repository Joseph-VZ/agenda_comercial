<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Comprobante de Venta</h1>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($cliente) ?></p>
    <p><strong>Vendedor:</strong> <?= htmlspecialchars($usuario) ?></p>
    <p><strong>Fecha:</strong> <?= htmlspecialchars($venta['fecha']) ?></p>

    <?php if (!empty($productos)): ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; foreach ($productos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td>$<?= number_format($p['precio_unitario'], 2) ?></td>
                        <td><?= $p['cantidad'] ?></td>
                        <td>$<?= number_format($p['subtotal'], 2) ?></td>
                    </tr>
                    <?php $total += $p['subtotal']; ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>$<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay productos para mostrar.</p>
    <?php endif; ?>
</body>
</html>
