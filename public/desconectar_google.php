<?php
session_start();

// Eliminar tokens y datos de Google de la sesión
unset($_SESSION['access_token']);
unset($_SESSION['calendar_id']);

// Redirigir al módulo de citas
header('Location: /agenda_comercial/public/index.php?modulo=citas');
exit;
