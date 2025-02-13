<?php
$opciones = $datos['opciones'] ?? [];

$html = '<div class="table-responsive">
        <table class="table table-sm table-striped">';
$html .= '<thead>
            <tr>
                <th>Editar</th>
                <th>Nombre</th>
                <th>URL</th>
                <th>Posición</th>
                <th>Nivel</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>';

if (!empty($opciones)) {
    foreach ($opciones as $opcion) {
        $html .= '<tr id="fila-' . htmlspecialchars($opcion['id']) . '">
    <td>
        <img src="img/edit.png" style="height:1.2em; cursor: pointer;"
        onclick="editarOpcion(' . htmlspecialchars($opcion['id']) . ')">
    </td>
    <td>' . htmlspecialchars($opcion['nombre']) . '</td>
    <td>' . htmlspecialchars($opcion['url']) . '</td>
    <td>' . htmlspecialchars($opcion['posicion']) . '</td>
    <td>' . htmlspecialchars($opcion['nivel']) . '</td>
    <td>
        <button class="btn btn-sm btn-danger"
                onclick="eliminarOpcion(' . htmlspecialchars($opcion['id']) . ')">
            Eliminar
        </button>
    </td>
</tr>';
    }
} else {
    $html .= '<tr>
                <td colspan="6" class="text-center">No se encontraron opciones.</td>
              </tr>';
}

$html .= '</tbody></table></div>';
echo $html;
?>
