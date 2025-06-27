<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: ../public/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="../template/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <h4>Iniciar Sesión</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="../procesar_login.php" autocomplete="off">
                        <div class="form-group">
                            <label>Correo electrónico</label>
                            <input type="email" name="correo" class="form-control" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" required autocomplete="new-password">
                        </div>
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger mt-2"><?= htmlspecialchars($_GET['error']) ?></div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary btn-block mt-3">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
