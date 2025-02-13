<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Menu.php';
require_once 'vistas/Vista.php';

class C_Menu extends Controlador {
    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new M_Menu();
    }

    public function mostrarMenu() {
        $opcionesMenu = $this->modelo->obtenerOpcionesMenu();
        Vista::render('vistas/V_Menu.php', array('opcionesMenu' => $opcionesMenu));
    }       
}
?>
