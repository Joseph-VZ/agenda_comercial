<?php
session_start();

// Revocar token si existe
if (isset($_SESSION['access_token'])) {
    require_once __DIR__ . '/../vendor/autoload.php';

    $client = new Google_Client();
    $client->setAccessToken($_SESSION['access_token']);

    if ($client->getAccessToken()) {
        $client->revokeToken();
    }
}

// Destruir toda la sesión
session_unset();
session_destroy();

// Redirigir de vuelta al inicio o módulo de citas
header('Location: index.php?modulo=citas');
exit;
