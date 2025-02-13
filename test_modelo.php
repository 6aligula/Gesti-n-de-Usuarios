<?php
require_once './modelos/M_Permisos.php';

$modelo = new M_Permisos();
$resultado = $modelo->obtenerOpcionesConPermisos();

// Mostrar el resultado en pantalla
echo '<pre>';
print_r($resultado);
echo '</pre>';
?>
