<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Roles.php';
require_once 'vistas/Vista.php';

class C_Roles extends Controlador {
    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new M_Roles();
    }

    public function getVistaNuevo() {
        Vista::render('vistas/Roles/V_Roles_NuevoEditar.php');
    }

    public function getVistaEditar($datos = []) {
        $rol = $this->modelo->obtenerPorId($datos['id']);
        Vista::render('vistas/Roles/V_Roles_NuevoEditar.php', ['rol' => $rol]);
    }

    public function guardarRol($datos = []) {
        $resultado = $this->modelo->guardarRol($datos);
        echo json_encode([
            'correcto' => $resultado ? 'S' : 'N',
            'msj' => $resultado ? 'Rol guardado correctamente' : 'Error al guardar el rol'
        ]);
    }

    public function eliminarRol($datos = []) {
        $resultado = $this->modelo->eliminarRol($datos['id']);
        echo json_encode([
            'correcto' => $resultado ? 'S' : 'N',
            'msj' => $resultado ? 'Rol eliminado correctamente' : 'Error al eliminar el rol'
        ]);
    }
}