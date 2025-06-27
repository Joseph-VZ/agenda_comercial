<?php
require_once '../../../config/database.php';
require_once '../../../models/Usuario.php';

$db = (new Database())->connect();
$model = new Usuario($db);

$id = $_POST['id'];
$model->eliminar($id);
