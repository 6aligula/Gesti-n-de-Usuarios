<?php
require_once 'vistas/Vista.php';

if (!function_exists('renderOpcionesTree')) {
    function renderOpcionesTree($opciones, $padre_id = null, $nivel_actual = 1) {
        $html = '';

        $hijos = array_filter($opciones, function ($opcion) use ($padre_id) {
            return $opcion['id_padre'] == $padre_id;
        });

        if (empty($hijos)) {
            return '';
        }

        $html .= '<ul class="menu-vertical">';
        foreach ($hijos as $opcion) {
            $html .= '<li>';
            $html .= '<div class="opcion-row" data-id="' . $opcion['id'] . '" data-posicion="' . $opcion['posicion'] . '" data-nivel="' . $nivel_actual . '">';
            $html .= '<span>' . htmlspecialchars($opcion['nombre']) . '</span>';
            $html .= ' <button class="edit" onclick="editarOpcion(' . htmlspecialchars($opcion['id']) . ')">📝</button>';
            $html .= ' <button class="delete" onclick="eliminarOpcion(' . htmlspecialchars($opcion['id']) . ')">🗑</button>';

            if ($nivel_actual === 1) {
                $html .= ' <button class="add-above" onclick="nuevaOpcionArriba(' . htmlspecialchars($opcion['id']) . ')">Añadir Arriba</button>';
                $html .= ' <button class="add-below" onclick="nuevaOpcionDebajo(' . htmlspecialchars($opcion['id']) . ')">Añadir Abajo</button>';
            }

            $html .= ' <button class="add-here" onclick="nuevaOpcion(' . htmlspecialchars($opcion['id']) . ', ' . (int)($nivel_actual + 1) . ')">Añadir Hijo</button>';
            $html .= '</div>';

            // ✅ Mostrar permisos debajo de cada opción
            $html .= '<ul class="permisos">';
            
            if (!empty($opcion['permisos'])) {
                foreach ($opcion['permisos'] as $permiso) {
                    $html .= '<li>';
                    $html .= '<span>' . htmlspecialchars($permiso['nombre']) . '</span>';
                    $html .= ' <button class="edit-permiso" onclick="editarPermiso(' . htmlspecialchars($permiso['id']) . ', this)">📝</button>';
                    $html .= ' <button class="delete-permiso" onclick="eliminarPermiso(' . htmlspecialchars($permiso['id']) . ')">🗑</button>';
                    $html .= '</li>';
                }
            } else {
                // no hay permisos
            }

            // ✅ Botón de "Añadir Permiso" siempre visible
            $html .= '<li><button class="add-permiso" onclick="nuevaPermiso(' . htmlspecialchars($opcion['id']) . ', this)">Añadir Permiso</button></li>';
            $html .= '</ul>'; 

            // ✅ Se asegura de continuar con las subopciones correctamente
            $html .= '<div class="new-option-form" id="new-option-form-' . $opcion['id'] . '" style="display:none;"></div>';
            $html .= renderOpcionesTree($opciones, $opcion['id'], $nivel_actual + 1);

            $html .= '</li>'; // Cierre correcto de cada opción
        }
        $html .= '</ul>'; // Cierre correcto de la lista principal

        return $html;
}
}
?>


<!-- Resto de tu código HTML -->

<div class="container">
    <!-- Menú Vertical -->
    <div class="sidebar">
        <h2>Menú Opciones</h2>
        <button id="nueva-opcion" class="btn-crear" onclick="nuevaOpcion()">Añadir Opción</button>
        <div id="opciones-listado">
            <?php if (!empty($opcionesMenu)): ?>
                <?= renderOpcionesTree($opcionesMenu); ?>
            <?php else: ?>
                <p>No hay opciones disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Crear/Editar Opciones -->
    <div id="capaEditarCrear" class="edit-container">
</div>

</div>


<!-- Estilos -->
<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f9;
    color: #333;
    margin: 0;
}

.container {
    width: 100%;
    display: flex;
    height: 100vh;
    padding: 20px;
    gap: 20px;
}

.menu-vertical {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
}

.menu-vertical > li {
    margin: 10px 0;
    background-color: #ffffff;
    border: 1px solid #e1e4e8;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    padding: 15px;
}

.menu-vertical > li:hover {
    background-color: #f0f8ff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.menu-vertical > li .parent-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-vertical span {
    flex-grow: 1;
    font-size: 1rem;
    color: #333;
    margin-right: 10px;
}

.menu-vertical button {
    margin-left: 8px;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: bold;
    transition: all 0.3s;
}

.menu-vertical .edit {
    background-color: #007bff;
    color: #ffffff;
}

.menu-vertical .edit:hover {
    background-color: #0056b3;
}

.menu-vertical .delete {
    background-color: #dc3545;
    color: #ffffff;
}

.menu-vertical .delete:hover {
    background-color: #c82333;
}

.menu-vertical .add-here {
    background-color: #28a745;
    color: #ffffff;
}

.menu-vertical .add-here:hover {
    background-color: #218838;
}

.menu-vertical ul {
    padding-left: 20px;
    margin-top: 10px;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.menu-vertical ul li {
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.menu-vertical ul li:hover {
    background-color: #e6f7ff;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.menu-vertical ul li span {
    flex-grow: 1;
    font-size: 0.95rem;
    color: #555;
    margin-right: 10px;
}

.menu-vertical ul li button {
    margin-left: 5px;
    padding: 6px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: bold;
    transition: all 0.3s;
}

.btn-crear {
    width: 100%;
    padding: 12px 15px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.btn-crear:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.content {
    flex: 1;
    padding: 20px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    display: none;
}

.content.active {
    display: flex;
    justify-content: center;
    align-items: center;
}

.edit-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 30px;
    width: 100%;
}

.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    padding: 20px;
    display: none;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modal-content {
    padding: 20px;
}

.close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    color: #dc3545;
    font-size: 18px;
}

.container-fluid form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    margin: 0 auto;
}

.container-fluid form .btn {
    width: 160px;
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    font-weight: bold;
    transition: all 0.2s;
}

.container-fluid form .btn-primary {
    background-color: #007bff;
    color: #ffffff;
    border: none;
}

.container-fluid form .btn-primary:hover {
    background-color: #0056b3;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
}

.container-fluid form .btn-secondary {
    background-color: #6c757d;
    color: #ffffff;
    border: none;
}

.container-fluid form .btn-secondary:hover {
    background-color: #5a6268;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
}

.container-fluid form input,
.container-fluid form select {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: border-color 0.2s;
}

.container-fluid form input:focus,
.container-fluid form select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
    outline: none;
}

.container-fluid form label {
    font-weight: bold;
    font-size: 0.95rem;
    color: #333;
    margin-bottom: 5px;
    display: block;
}

.new-option-form {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
    background: #f9f9f9;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    gap: 15px;
}

.new-option-form label {
    margin-right: 5px;
    font-size: 0.9rem;
    font-weight: bold;
}

.new-option-form input {
    flex: 1;
    max-width: 200px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 0.9rem;
}

.new-option-form button {
    margin-left: 10px;
    padding: 8px 15px;
    font-size: 0.9rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    background-color: #007bff;
    color: #fff;
    transition: background-color 0.3s;
}

.new-option-form button:hover {
    background-color: #0056b3;
}

.new-option-form .btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

.new-option-form .btn-secondary:hover {
    background-color: #5a6268;
}

</style>













