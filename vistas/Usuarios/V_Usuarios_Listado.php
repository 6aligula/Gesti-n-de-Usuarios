<?php
$usuarios = $datos['usuarios'] ?? [];

$html = '<div class="table-responsive">
        <table class="table table-sm table-striped">';
$html .= '<thead>
            <tr>
                <th>Editar</th>
                <th>Apellidos, Nombre</th>
                <th>Email</th>
                <th>Login</th>
                <th>¿Activo?</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>';

if (!empty($usuarios)) {
    foreach ($usuarios as $fila) {
        $claseFila = ($fila['activo'] === 'N') ? 'usuario-inactivo' : 'usuario-activo';
        $estadoTexto = ($fila['activo'] === 'N') ? 'Inactivo' : 'Activo';
        $botonTexto = ($fila['activo'] === 'N') ? 'Activar' : 'Desactivar';

        $html .= '<tr id="fila-' . htmlspecialchars($fila['id_Usuario']) . '" class="' . $claseFila . '">
            <td>
                <img src="img/edit.png" style="height:1.2em;"
                onclick="obtenerVista_EditarCrear(\'Usuarios\',\'getVistaNuevoEditar\',\'capaEditarCrear\', \'' . htmlspecialchars($fila['id_Usuario']) . '\')">
            </td>
            <td nowrap>' . htmlspecialchars($fila['apellido_1']) . ' ' . htmlspecialchars($fila['apellido_2']) . ', ' . htmlspecialchars($fila['nombre']) . '</td>
            <td>' . htmlspecialchars($fila['mail']) . '</td>
            <td>' . htmlspecialchars($fila['login']) . '</td>
            <td id="estado-' . htmlspecialchars($fila['id_Usuario']) . '">' . $estadoTexto . '</td>
            <td>
                <button id="btn-toggle-' . htmlspecialchars($fila['id_Usuario']) . '" class="btn btn-sm btn-primary"
                        onclick="toggleUserStatus(' . htmlspecialchars($fila['id_Usuario']) . ', \'' . $estadoTexto . '\')">
                    ' . $botonTexto . '
                </button>
            </td>
        </tr>';
    }
} else {
    $html .= '<tr>
                <td colspan="6" class="text-center">No se encontraron usuarios.</td>
              </tr>';
}

$html .= '</tbody></table></div>';
echo $html;
?>
