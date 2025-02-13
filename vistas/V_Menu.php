<?php
if (!isset($opcionesMenu)) {
    echo "Variable \$menu no está definida en la vista.";
    return;
}

if (!is_array($opcionesMenu)) {
    echo "Variable \$menu no es un array en la vista.";
    return;
}

if (empty($opcionesMenu)) {
    echo "El array \$menu está vacío en la vista.";
    return;
}

?>

<div class="container-fluid" id="capaMenu">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <?php foreach ($opcionesMenu as $opcion) : ?>
                        <?php if (empty($opcion['id_padre'])) : // Nivel 1 
                        ?>
                            <?php
                            // Buscar submenús asociados a esta opción
                            $submenus = array_filter($opcionesMenu, fn($item) => $item['id_padre'] == $opcion['id']);
                            ?>
                            <?php if (empty($submenus)) : ?>
                                <!-- Opción de nivel 1 sin submenús -->
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="<?= htmlspecialchars($opcion['url'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?php if ($opcion['nombre'] === 'Usuarios') : ?>
                                        onclick="obtenerVista('Usuarios', 'getVistaFiltros', 'capaContenido')"
                                        <?php elseif ($opcion['nombre'] === 'Menu') : ?>
                                        onclick="obtenerVista('Menu', 'mostrarMenu', 'capaContenido')"
                                        <?php endif; ?>>
                                        <?= htmlspecialchars($opcion['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                </li>
                            <?php else : ?>
                                <!-- Opción de nivel 1 con submenús -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= htmlspecialchars($opcion['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($submenus as $submenu) : ?>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="<?= htmlspecialchars($submenu['url'], ENT_QUOTES, 'UTF-8') ?>"
                                                    <?php if ($submenu['nombre'] === 'Usuarios') : ?>
                                                    onclick="obtenerVista('Usuarios', 'getVistaFiltros', 'capaContenido')"
                                                    <?php elseif ($submenu['nombre'] === 'Menu') : ?>
                                                    onclick="obtenerVista('Opciones', 'getVistaFiltros', 'capaContenido')"
                                                    <?php elseif ($submenu['nombre'] === 'Mostrar Opciones') : ?>
                                                    onclick="obtenerVista('Opciones', 'mostrarOpciones', 'capaContenido')"
                                                    <?php endif; ?>>
                                                    <?= htmlspecialchars($submenu['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </ul>
            </div>
        </div>
    </nav>
</div>