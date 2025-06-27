<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->connect(); 

    
    $usuarioModel = new Usuario($db);

  
    $usuario = $usuarioModel->obtenerPorCorreo($correo);

    if ($usuario && password_verify($password, $usuario['password'])) {

        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'correo' => $usuario['correo'],
            'rol' => $usuario['rol']
        ];
        header('Location: ../views/inicio.php');
    } else {
        header('Location: ../views/login.php?error=1');
    }
    exit;
}
