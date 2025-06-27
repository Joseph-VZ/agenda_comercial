<?php
session_start();
require_once 'config/database.php';
require_once 'models/Usuario.php';

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($correo) || empty($password)) {
    header("Location: views/login.php?error=Campos+obligatorios");
    exit();
}

$database = new Database();
$db = $database->connect();
$usuarioModel = new Usuario($db);
$usuario = $usuarioModel->obtenerPorCorreo($correo);

if ($usuario && password_verify($password, $usuario['password'])) {
    $_SESSION['usuario'] = $usuario;
    header("Location: public/index.php");
    exit();
} else {
    header("Location: views/login.php?error=Credenciales+inválidas");
    exit();
}
