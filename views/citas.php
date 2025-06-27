<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../middleware/auth.php';

// Procesa el logout de Google antes de cualquier HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desconectar_google'])) {
    unset($_SESSION['access_token']);
    unset($_SESSION['calendar_id']);
    header('Location: ../index.php?modulo=citas');
    exit;
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo de Citas</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
</head>

<body class="bg-light">


<?php if (isset($_SESSION['access_token'])): ?>
    <div class="alert alert-success text-center m-3">
        <i class="fab fa-google"></i> Conectado con Google Calendar
    </div>
<?php endif; ?>

<!-- Contenido del módulo -->
<?php include __DIR__ . '/contenido_citas.php'; ?>

<script src="../js/citas/citas.js"></script>

</body>
</html>
