<?php
require_once '../../../config/database.php';
require_once '../../../models/Usuario.php';

$db = (new Database())->connect();
$model = new Usuario($db);

$id = $_GET['id'];
$usuario = $model->obtenerPorId($id);
echo json_encode($usuario);
