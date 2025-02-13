<h2>Gestión de Opciones del Menú</h2>
<div class="container-fluid" id="capaFiltrosBusqueda">
    <form id="formularioBuscarOpciones" name="formularioBuscarOpciones">    
        <div class="row">
            <!-- Nuevo select para usuarios -->
            <div class="form-group col-md-4">
                <label for="usuario">Usuario:</label>
                <select id="usuario" name="usuario" class="form-control">
                    <option value="">Seleccione un usuario</option>
                    <?php foreach ($datos['usuarios'] as $usuario): ?>
                        <option value="<?= $usuario['id_Usuario'] ?>">
                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido_1'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Nuevo select para roles -->
            <div class="form-group col-md-4">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" class="form-control">
                    <option value="">Seleccione un rol</option>
                    <?php foreach ($datos['roles'] as $rol): ?>
                        <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            

        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" onclick="buscarPermisos()">Buscar</button>
            </div>
        </div>
    </form>
    <div class="container-fluid" id="capaResultadoBusqueda"></div>
    <div class="container-fluid" id="capaEditarCrear"></div>
</div>

<script>
// Hacer que los selects sean mutuamente excluyentes
document.getElementById('usuario').addEventListener('change', function() {
    if (this.value) document.getElementById('rol').value = '';
});

document.getElementById('rol').addEventListener('change', function() {
    if (this.value) document.getElementById('usuario').value = '';
});
</script>
