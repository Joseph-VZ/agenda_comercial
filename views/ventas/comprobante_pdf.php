<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; margin: 40px; color: #000; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .titulo { font-size: 16pt; font-weight: bold; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; font-size: 10pt; }
        th { background-color: #f2f2f2; }
        .total-row td { font-weight: bold; background-color: #fafafa; }
    </style>
</head>
<body>
    <div class="header">
        <!-- <img src="../assets/img/logo_empresa.jpg" class="logo" style="width: 120px;"> -->
        <div class="titulo">Comprobante de Venta #<?= htmlspecialchars($venta['id']) ?></div>
    </div>

    <p><strong>Cliente:</strong> <?= htmlspecialchars($nombre_cliente) ?></p>
    <p><strong>Vendedor:</strong> <?= htmlspecialchars($nombre_usuario) ?></p>
    <p><strong>Fecha:</strong> <?= htmlspecialchars($venta['fecha']) ?></p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($productos as $p): ?>
                <?php $total += $p['subtotal']; ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td>$<?= number_format($p['precio_unitario'], 2) ?></td>
                    <td><?= $p['cantidad'] ?></td>
                    <td>$<?= number_format($p['subtotal'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total:</td>
                <td>$<?= number_format($total, 2) ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
