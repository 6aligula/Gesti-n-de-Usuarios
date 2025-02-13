<div class="menu-permisos">
    <?php foreach ($opciones as $opcion): ?>
        <div class="opcion-menu">
            <span><?= htmlspecialchars($opcion['nombre']) ?></span>
            <?php if (!empty($opcion['permisos'])): ?>
                <ul class="permisos-lista">
                    <?php foreach ($opcion['permisos'] as $permiso): ?>
                        <li>
                            <label>
                                <input type="checkbox" 
                                       onchange="togglePermiso(this, <?= $permiso['id'] ?>)"
                                       <?= in_array($permiso['id'], $permisosAsignados) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($permiso['nombre']) ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<style>
.menu-permisos {
    margin-top: 20px;
}

.opcion-menu {
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.permisos-lista {
    list-style: none;
    margin: 5px 0 0 20px;
    padding: 0;
}

.permisos-lista li {
    margin: 5px 0;
}
</style>