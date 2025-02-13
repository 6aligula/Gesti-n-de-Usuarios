<?php
$id = $datos['opcion']['id'] ?? '';
$nombre = $datos['opcion']['nombre'] ?? '';
$url = $datos['opcion']['url'] ?? '';
$id_padre = $_GET['id_padre'] ?? ($datos['opcion']['id_padre'] ?? null);
$posicion = isset($datos['posicion']) ? $datos['posicion'] : ($_GET['posicion'] ?? '1');
$nivel = $_GET['nivel'] ?? ($datos['opcion']['nivel'] ?? 1);

// Ajustar valores predeterminados para opciones principales
$id_padre = $id_padre !== '' ? $id_padre : null;
$nivel = $id_padre === null ? 1 : $nivel;
?>

<h2><?= $id ? 'Editar Opción' : 'Nueva Opción' ?></h2>
<div class="container-fluid">
    <form id="formularioOpcion">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($nombre) ?>" required>
        </div>
        <div class="form-group">
            <label for="url">URL:</label>
            <input type="text" id="url" name="url" class="form-control" value="<?= htmlspecialchars($url) ?>">
        </div>
        <div class="form-group">
            <label for="id_padre">Padre:</label>
            <input type="number" id="id_padre" name="id_padre" class="form-control" value="<?= htmlspecialchars($id_padre) ?>">
        </div>
        <div class="form-group">
            <label for="posicion">Posición:</label>
            <input type="number" id="posicion" name="posicion" class="form-control" value="<?= htmlspecialchars($posicion) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="nivel">Nivel:</label>
            <input type="number" id="nivel" name="nivel" class="form-control" value="<?= htmlspecialchars($nivel) ?>">
        </div>

        <button type="button" class="btn btn-primary" onclick="guardarOpcion()">Guardar</button>
        <button type="button" class="btn btn-secondary" onclick="cancelarEdicion()">Cancelar</button>
    </form>
</div>
