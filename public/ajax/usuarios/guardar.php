<?php
require_once '../../../config/database.php';
require_once '../../../models/Usuario.php';

$db = (new Database())->connect();
$model = new Usuario($db);

$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];

if ($id) {
    $model->actualizar($id, $nombre, $correo);
} else {
    $model->crear($nombre, $correo);
}
