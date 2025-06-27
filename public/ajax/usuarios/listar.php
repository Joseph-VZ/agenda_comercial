<?php
require_once '../../../config/database.php';
require_once '../../../models/Usuario.php';

$db = (new Database())->connect();
$model = new Usuario($db);
$usuarios = $model->obtenerTodos();

foreach ($usuarios as $u) {
    echo "<tr>
            <td>{$u['id']}</td>
            <td>{$u['nombre']}</td>
            <td>{$u['correo']}</td>
            <td>
                <button class='btn btn-sm btn-primary btnEditar' data-id='{$u['id']}'>Editar</button>
                <button class='btn btn-sm btn-danger btnEliminar' data-id='{$u['id']}'>Eliminar</button>
            </td>
        </tr>";
}
