<div id="capaEditarCrear" style="position: absolute; display: block; z-index: 1000;">
    <form id="formularioPermiso" method="post" onsubmit="guardarPermiso(); return false;">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id ?? '') ?>">
        <input type="hidden" name="id_Menu" value="<?= htmlspecialchars($id_Menu ?? '') ?>">

        <div>
            <label for="nombre">Nombre del Permiso:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($permiso ?? '') ?>" required>
        </div>

        <div>
            <label for="codigo">Código del Permiso:</label>
            <input type="text" id="codigo" name="codigo" value="<?= htmlspecialchars($codigo_Permiso ?? '') ?>" required>
        </div>

        <div>
            <button type="submit">Guardar</button>
            <button type="button" onclick="cancelarEdicion()">Cancelar</button>
        </div>
    </form>
</div>
