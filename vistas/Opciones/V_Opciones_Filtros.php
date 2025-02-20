<h2>Gestión de Opciones del Menú</h2>
<div class="container-fluid" id="capaFiltrosBusqueda">
    <form id="formularioBuscarOpciones" name="formularioBuscarOpciones">
        <div class="row">
            <!-- Select para usuarios -->
            <div class="form-group col-md-4">
                <label for="usuario">Usuario:</label>
                <select id="usuario" name="usuario" class="form-control">
                    <option value="">Seleccione un usuario</option>
                    <?php foreach ($datos['usuarios'] as $usuario): ?>
                        <option value="<?= $usuario['id_Usuario'] ?>"
                            data-roles="<?= htmlspecialchars(json_encode($usuario['roles'] ?? []), ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido_1'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Select para roles con botones de gestión -->
            <div class="form-group col-md-4">
                <label for="rol">Rol:</label>
                <div class="d-flex gap-2">
                    <select id="rol" name="rol" class="form-control">
                        <option value="">Seleccione un rol</option>
                        <?php foreach ($datos['roles'] as $rol): ?>
                            <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="role-actions">
                        <!-- Botón Crear: visible por defecto -->
                        <button type="button" class="btn btn-success" id="btnCrearRol" onclick="crearRol()">
                            <i class="fas fa-plus"></i>
                        </button>
                        <!-- Botón Editar: oculto inicialmente con d-none -->
                        <button type="button" class="btn btn-primary d-none" id="btnEditarRol" onclick="editarRol()">
                            <i class="fas fa-edit"></i>
                        </button>
                        <!-- Botón Eliminar: oculto inicialmente con d-none -->
                        <button type="button" class="btn btn-danger d-none" id="btnEliminarRol" onclick="eliminarRol()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <small id="rolesUsuario" class="form-text text-muted mt-1"></small>
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